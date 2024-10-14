<?php

namespace Modules\User\Communication\Http\Controllers;

use Modules\Foundation\Http\Controllers\BaseController;
use Modules\User\Communication\DataTables\NotificationDataTable;
use Modules\User\Communication\Http\Requests\NotificationRequest;
use Modules\User\Communication\Models\Notification;
use Mockery\Exception;

/**
 * Class NotificationController
 * @package Modules\User\Communication\Http\Controllers
 */
class NotificationController extends BaseController
{

    /**
     * NotificationController constructor.
     */
    public function __construct()
    {
        $this->resource_url = config('notification.models.notification.resource_url');

        $this->title = 'Notification::module.notification.title';
        $this->title_singular = 'Notification::module.notification.title_singular';

        $this->setViewSharedData(['hideCreate' => true]);
        parent::__construct();
    }

    /**
     * @param NotificationRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(NotificationRequest $request, NotificationDataTable $dataTable)
    {
        $showCreateButton = false;
        return $dataTable->render('Notification::notification.index', compact('showCreateButton'));
    }

    /**
     * @param NotificationRequest $request
     * @param Notification $notification
     * @return $this
     */
    public function show(NotificationRequest $request, Notification $notification)
    {
        $this->setViewSharedData(['title_singular' => trans('Modules::labels.show_title', ['title' => $this->title_singular])]);

        $notification->markAsRead();

        return view('Notification::notification.show')->with(compact('notification'));
    }

    /**
     * @param NotificationRequest $request
     * @param Notification $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function readAtToggle(NotificationRequest $request, Notification $notification)
    {
        try {
            $notification->toggleReadAt();
            $message = ['level' => 'success', 'message' => trans('Modules::messages.success.updated', ['item' => 'Notification'])];
        } catch (Exception $exception) {
            log_exception($exception, Notification::class, 'readAtToggle');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

}
