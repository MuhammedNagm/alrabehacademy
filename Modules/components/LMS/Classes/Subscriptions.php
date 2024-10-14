<?php

namespace Modules\Components\LMS\Classes;

use Carbon\Carbon;
use Modules\Components\LMS\Models\Category;
use Modules\Components\LMS\Models\Quiz;
use Modules\Components\LMS\Models\Content;
use Modules\Components\LMS\Models\Course;
use Modules\Components\LMS\Models\Tag;
use Modules\Components\LMS\Models\UserLMS;
use Modules\User\Models\User;
use Modules\Components\LMS\Models\Plan;
use Modules\Components\LMS\Models\Invoice;
use Modules\Components\LMS\Models\Logs;
use Modules\Components\LMS\Models\Subscription;
use Illuminate\Support\Facades\DB;


class Subscriptions
{
    /**
     * LMS constructor.
     */
    function __construct()
    {
    }


    /**
     * check if auth user is subscribed to item.
     * @throws \Exception
     */

      public function check_subscription($attributes = []){

        $user = $attributes['user'];
        $module = $attributes['module'];
        $module_id = $attributes['module_id'];

              $userSubscription = Subscription::where('user_id', $user->id)
                  ->where('subscriptionnable_type', $module)
                  ->where('subscriptionnable_id',  $module_id)
                  ->where(function($query){
                      $query->whereHas('invoice', function ($invoice){
                          $invoice->where(function($q_invoice) use ($invoice) {
                              $q_invoice->whereHas('coupon', function ($coupon){
                                  $coupon->where('expiry','>=',Carbon::now());
                              });
                              $q_invoice->orWhere('coupon_id', null);
                          });

                      });
                      $query->orWhere('invoice_id', null);
                  })->get();

        if($userSubscription->count()){

            $activeSubscription = $userSubscription->where('status', 1)->first();
            if($activeSubscription){
                $status = 1;
            }else{
                $activeSubscription = $userSubscription->first();
                $status = $activeSubscription->status;
            }

            return ['success' => true, 'status' =>  $status,'data' => $activeSubscription, 'message' => 'subscribed'.$status];
        }

         return   ['success' => false, 'status' =>  0,'data' => null, 'message' => 'not subscribed']; //@transM
    }


     /**
     * check if auth user is subscribed to item.
     */

      public function is_subscribed($attributes = []){

        $user = $attributes['user'];
        $module = $attributes['module'];
        $module_id = $attributes['module_id'];


              $userSubscription = $user->subscriptions()->where('subscriptionnable_type', $module)->where('subscriptionnable_id',  $module_id)
                  ->where(function($query){
                      $query->whereHas('invoice', function ($invoice){
                          $invoice->where(function($q_invoice) use ($invoice) {
                          $q_invoice->whereHas('coupon', function ($coupon){
                              $coupon->where('expiry','>=',Carbon::now());
                          });
                              $q_invoice->orWhere('coupon_id', null);
                          });

                      });
                      $query->orWhere('invoice_id', null);
                  });

           if($userSubscription->count()){


            return true;
        }


         return  false;

    }


     public function planned($attributes = []){

        //success => true >> have plan
        //status => 1 >> active subscription

        //success => true & status = 0 >> have plan but sub. not active

        $user = $attributes['user'];
        $module = $attributes['module'];
        $module_id = $attributes['module_id'];

             $userSubscribedPlans = $user->subscriptions()->where('subscriptionnable_type', 'plan')
                 ->where(function($query){
                     $query->whereHas('invoice', function ($invoice){
                         $invoice->where(function($q_invoice) use ($invoice) {
                             $q_invoice->whereHas('coupon', function ($coupon){
                                 $coupon->where('expiry','>=',Carbon::now());
                             });
                             $q_invoice->orWhere('coupon_id', null);
                         });

                     });
                     $query->orWhere('invoice_id', null);
                 })->get();


        if(!$userSubscribedPlans->count()){
                $userSubscribedPlans = $user->subscriptions()->where('subscriptionnable_type', 'plan')
                    ->where('invoice_id', null)->get();

        }

        if(!$userSubscribedPlans->count()){
         return  ['success' => false, 'status' => 0, 'plan' => null,  'message' => 'not have plan'];
        }

        $userSubscribedPlansActiveIds = $userSubscribedPlans->where('status', 1)->pluck('subscriptionnable_id')->toArray();


        if(count($userSubscribedPlansActiveIds)){

        $firstUserPlannable = DB::table('lms_plannables')->where('lms_plannable_type', $module)->where('lms_plannable_id', $module_id)->whereIn('plan_id', $userSubscribedPlansActiveIds)->first();

        if($firstUserPlannable){

           $firstPlan = Plan::find($firstUserPlannable->plan_id);

           $subPlan = $userSubscribedPlans->where('subscriptionnable_id', $firstPlan->id)->first();

           $plan_status = 0;

           if($subPlan){
            $plan_status= $subPlan->status;
           }

        return  ['success' => true, 'status' =>  $plan_status, 'plan' => $firstPlan,  'message' => 'have active plan'];
        }      

        }


         $userSubscribedPlansIdS = $userSubscribedPlans->pluck('subscriptionnable_id')->toArray();


            //first plannable item for user

         $firstUserPlannable = DB::table('lms_plannables')->whereIn('plan_id', $userSubscribedPlansIdS)->where('lms_plannable_type', $module)->where('lms_plannable_id',  $module_id)->orderBy('created_at', 'desc')->orderBy('status', 'desc')->first();



         if($firstUserPlannable){
            $firstPlan = Plan::find($firstUserPlannable->plan_id);

        $subPlan = $userSubscribedPlans->where('subscriptionnable_type', 'plan')->where('subscriptionnable_id', $firstPlan->id)->first();
           $plan_status = 0;
           if($subPlan){
            $plan_status= $subPlan->status;
           }


         return  ['success' => true, 'status' => $plan_status, 'plan' => $firstPlan,  'message' => 'have plan'];

        }

             $moduleCategoriesIds = DB::table('lms_categoriables')->where('lms_categoriable_type', $module)->where('lms_categoriable_id',  $module_id)->pluck('lms_categoriables.category_id')->toArray();



            $moduleCategoriesData  = Category::whereIn('id',$moduleCategoriesIds)->get();
               if($moduleCategoriesData->count()){
                 foreach ($moduleCategoriesData as $rowCat) {
                   $parentCategoryIds = $rowCat->getParents($rowCat);
                   $moduleCategoriesIds = array_merge($moduleCategoriesIds, $parentCategoryIds);
                    }
                   } 


        $categoriesActivePlan = DB::table('lms_plannables')->whereIn('plan_id', $userSubscribedPlansActiveIds)->where('lms_plannable_type', 'category')->whereIn('lms_plannable_id',  $moduleCategoriesIds)->orderBy('created_at', 'desc')->orderBy('status', 'desc')->first();

         if(!empty($categoriesActivePlan)){
             $firstPlan = Plan::find($categoriesActivePlan->plan_id);
           $subPlan = $userSubscribedPlans->where('subscriptionnable_type', 'plan')->where('subscriptionnable_id', $firstPlan->id)->first();
           $plan_status = 0;
           if($subPlan){
            $plan_status= $subPlan->status;
           }
             return  ['success' => true, 'status' => $plan_status, 'plan' =>$firstPlan,  'message' => 'have plan'];

        }

        $categoriesPlan = DB::table('lms_plannables')->whereIn('plan_id', $userSubscribedPlansIdS)->where('lms_plannable_type', 'category')->whereIn('lms_plannable_id',  $moduleCategoriesIds)->orderBy('created_at', 'desc')->orderBy('status', 'desc')->first();

        if($categoriesPlan){
           

             $firstPlan = Plan::find($categoriesPlan->plan_id);

        $subPlan = $userSubscribedPlans->where('subscriptionnable_type', 'plan')->where('subscriptionnable_id', $firstPlan->id)->first();
           $plan_status = 0;
           if($subPlan){
            $plan_status= $subPlan->status;
           }
              

        return  ['success' => true, 'status' => $plan_status, 'plan' =>$firstPlan,  'message' => 'have plan'];

        }

         return  ['success' => false, 'status' => 0, 'plan' => null,  'message' => 'not have plan'];

     }


      public function subscribe($moduleArray = [], $invoiceArray = []){
        $user = $moduleArray['user'];
        $module = $moduleArray['module'];
        $module_id = $moduleArray['module_id'];


        $response = $this->is_subscribed($moduleArray);

          if($response){
            return  ['success' => false, 'status' => 0, 'message' => 'Subscribed'];
        }

        $response = $this->planned($moduleArray);


        if($response['success']){

            $userSubscriptions = Subscription::where('user_id', $user->id)->where('subscriptionnable_type', $module)->where('subscriptionnable_id',  $module_id)->update(['status' => 0]);


           $subscription = Subscription::Create([
                'user_id' =>  $user->id,
                'subscriptionnable_type' => $module,
                'subscriptionnable_id' => $module_id,
                'status' => $response['plan']->status,
                'plan_id' => $response['plan']->id,

            ]);

            return  ['success' => true, 'status' => 1, 'subscription' =>  $subscription, 'message' => 'done with plans'];

        }

       $response = $this->createInvoice($moduleArray, $invoiceArray);


       $subscription = Subscription::Create([
                'user_id' =>  $user->id,
                'subscriptionnable_type' => $module,
                'subscriptionnable_id' => $module_id,
                'status' => $response['status'],
                'invoice_id' => $response['invoice']?$response['invoice']->id:null

            ]);

       
        return  ['success' => true, 'status' =>  $response['status'], 'subscription' =>  $subscription, 'message' => 'done with plans'];

      }

     public function createInvoice($moduleArray = [], $invoiceArray = []){

        if(empty($invoiceArray)){

     return  ['success' => false, 'status' => 0, 'invoice' =>  null, 'message' => 'invoice pramater cannot be empty array'];


        }

        //module

        $status = 'pending';

        $user = $moduleArray['user'];
        $module = $moduleArray['module'];
        $module_id = $moduleArray['module_id'];

       //invoices
         $price = $invoiceArray['price'];
         $currency = $invoiceArray['currency'];
         $paid = $invoiceArray['paid'];
            if($price == 0){

             $paid = 0;

         }
         $coupon_id = isset($invoiceArray['coupon_id'])?$invoiceArray['coupon_id']:null;

         if($price <= $paid || $price <= 0){
             $status = 'paid';
         }else{
             $status = 'pending';
         }

         $codeString = $user->id.'invoices';

         $invoce = Invoice::Create([
                'user_id' =>  $user->id,
                'total_price' => $price,
                'currency' =>  $currency,
                'status' => $status,
                'coupon_id' => $coupon_id,
                'code' => \LMS::codeGenerator(11, true ,'Ra_',$user->id)
            ]);

        $invoicables = DB::table('lms_invoicables')->insert([

            'code' => \LMS::codeGenerator(11, true ,'Ra_',$user->id),
            'paid' => $paid,
            'price' => $price,
            'amount' => 1,
            'lms_invoicable_type' => $module,
            'lms_invoicable_id' => $module_id,
            'invoice_id' => $invoce->id,
            'coupon_id' => $coupon_id,
            'created_by' => Auth()->id(),
            'updated_by' => Auth()->id()

        ]);


    return  ['success' => true, 'status' => ($status == 'paid')?1:0, 'invoice' =>   $invoce, 'message' => 'invoice created'];



       }

    public function expiredSubscriptions($user = null){
          if(!$user){
              $user = UserLMS::find(Auth()->id());
          }

            $mainSubscriptionsData = Subscription::where('user_id', $user->id)
                ->whereHas('invoice', function ($invoice){
                    $invoice->whereHas('coupon', function ($coupon){
                        $coupon->where('expiry','<',Carbon::now());
                    });
                })->get();


        $mainSubscriptionsPlansIds = $mainSubscriptionsData->where('subscriptionnable_type', 'plan')->pluck('subscriptionnable_id')->toArray();
            $subSubscriptionsIds = Subscription::where('user_id', $user->id)
                ->whereIn('plan_id', $mainSubscriptionsPlansIds)->pluck('id')->toArray();


          return array_merge($mainSubscriptionsData->pluck('id')->toArray(), $subSubscriptionsIds);



       }





}