<?php

namespace Modules\Components\Utility\Http\Controllers\Rating;

use Modules\Foundation\Http\Controllers\BaseController;
use Modules\Components\Utility\Classes\Rating\RatingManager;
use Modules\Components\Utility\Http\Requests\Rating\RatingRequest;

class RatingBaseController extends BaseController
{
    protected $rateableClass = null;
    protected $redirectUrl = null;
    protected $successMessage = 'Utility::messages.rating.success.add';

    public function __construct()
    {
        $this->setCommonVariables();

        parent::__construct();
    }

    protected function setCommonVariables()
    {
        $this->rateableClass = null;
        $this->redirectUrl = null;
    }

    /**
     * @param RatingRequest $request
     * @param $rateable_hashed_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function createRating(RatingRequest $request, $rateable_hashed_id)
    {


        try {

            if (is_null($this->rateableClass)) {
                abort(400);
            }

            $rateable = $this->rateableClass::findByHash($rateable_hashed_id);

            if (!$rateable) {
                abort(404);
            }

            $data = $request->all();

            $ratingClass = new RatingManager($rateable, user());

            $ratingClass->handleModelRating([
                'rating' => $data['review_rating'],
                'title' => $data['review_subject'],
                'body' => $data['review_text']
            ]);

            $message = ['level' => 'success', 'message' => trans($this->successMessage)];
        } catch (\Exception $exception) {
            log_exception($exception, get_class($this), 'createRating');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }
        if ($request->ajax() || is_null($this->redirectUrl) || $request->wantsJson()) {
            return response()->json($message);
        } else {
            if ($message['level'] === 'success') {
                flash($message['message'])->success();
            } else {
                flash($message['message'])->error();
            }
            redirectTo($this->redirectUrl);
        }
    }
}
