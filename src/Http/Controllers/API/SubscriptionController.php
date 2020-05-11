<?php namespace Riari\Forum\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Riari\Forum\Models\ForumSubscription;

class SubscriptionController extends BaseController
{
    /**
     * Return the model to use for this controller.
     *
     * @return ForumSubscription
     */
    protected function model()
    {
        return new ForumSubscription();
    }

    /**
     * Return the translation file name to use for this controller.
     *
     * @return string
     */
    protected function translationFile()
    {
        return 'subscriptions';
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'subscribable_id' => ['required'],
            'subscribable_type' => ['required'],
            'user_id' => ['required'],
        ]);

        $subscription = (new ForumSubscription())->newQuery()
            ->where('subscribable_id', $request->input('subscribable_id'))
            ->where('subscribable_type', $request->input('subscribable_type'))
            ->where('user_id', $request->input('user_id'))
            ->first();

        if (!$subscription) {
            ForumSubscription::create($request->only([
                'subscribable_id',
                'subscribable_type',
                'user_id',
            ]));
        }

        return $this->response($subscription);
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function unsubscribe(Request $request)
    {
        $this->validate($request, [
            'subscribable_id' => ['required'],
            'subscribable_type' => ['required'],
            'user_id' => ['required'],
        ]);

        $subscription = (new ForumSubscription())->newQuery()
            ->where('subscribable_id', $request->input('subscribable_id'))
            ->where('subscribable_type', $request->input('subscribable_type'))
            ->where('user_id', $request->input('user_id'))
            ->first();

        if ($subscription) {
            $subscription->delete();
        }

        return $this->response($subscription);
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function isSubscribed(Request $request)
    {
        $this->validate($request, [
            'subscribable_id' => ['required'],
            'subscribable_type' => ['required'],
            'user_id' => ['required'],
        ]);

        $subscription = (new ForumSubscription())->newQuery()
            ->where('subscribable_id', $request->input('subscribable_id'))
            ->where('subscribable_type', $request->input('subscribable_type'))
            ->where('user_id', $request->input('user_id'))
            ->first();

        return $this->response([
            'is_subscribed' => ($subscription) ? 'yes' : 'no',
            'subscription' => $subscription
        ]);
    }
}
