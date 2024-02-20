<?php

namespace Webkul\Store\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\Store\Mail\Customer\RegistrationNotification;
use Webkul\Store\Mail\Customer\EmailVerificationNotification;
use Webkul\Store\Mail\Customer\UpdatePasswordNotification;
use Webkul\Store\Mail\Customer\NoteNotification;
use Webkul\Store\Mail\Customer\SubscriptionNotification;

class Customer extends Base
{
    /**
     * After customer is created
     *
     * @param  \Webkul\Customer\Contracts\Customer  $customer
     * @return void
     */
    public function afterCreated($customer)
    {
        if (core()->getConfigData('customer.settings.email.verification')) {
            try {
                if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.verification')) {
                    return;
                }

                Mail::queue(new EmailVerificationNotification($customer));
            } catch (\Exception $e) {
                \Log::info('EmailVerificationNotification Error');
                
                report($e);
            }

            return;
        }

        try {
            if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.registration')) {
                return;
            }

            Mail::queue(new RegistrationNotification($customer));
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Send mail on updating password.
     *
     * @param  \Webkul\Customer\Models\Customer  $customer
     * @return void
     */
    public function afterPasswordUpdated($customer)
    {
        try {
            Mail::queue(new UpdatePasswordNotification($customer));
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Send mail on subscribe
     *
     * @param  \Webkul\Customer\Models\Customer  $customer
     * @return void
     */
    public function afterSubscribed($customer)
    {
        try {
            Mail::queue(new SubscriptionNotification($customer));
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Send mail on creating Note
     *
     * @param  \Webkul\Customer\Models\Customer  $customer
     * @return void
     */
    public function afterNoteCreated($note)
    {
        if (! request()->has('customer_notified')) {
            return;
        }

        try {
            Mail::send(new NoteNotification($note, request()->input('note', 'email')));
        } catch (\Exception $e) {
            session()->flash('warning', $e->getMessage());
        }
    }
}
