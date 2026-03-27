<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = auth()->user();

        $course = Course::findOrFail($request->course_id);

        $alreadyBought = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'paid')
            ->exists();

        if ($alreadyBought) {
            return response()->json([
                "message" => "Already purchased"
            ], 409);
        }

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'pending'
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {

            $paymentIntent = PaymentIntent::create([
                'amount' => $course->price * 100,
                'currency' => 'mad',
                'payment_method' => 'pm_card_visa',
                'confirm' => true,
      
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
                
                'description' => 'Course: ' . $course->title,
            ]);

            $enrollment->update([
                'status' => 'paid'
            ]);

            Payment::create([
                'enrollment_id' => $enrollment->id,
                'stripe_payment_id' => $paymentIntent->id,
                'amount' => $course->price,
                'status' => 'success'
            ]);

            return response()->json([
                "message" => "Payment successful",
                "payment_intent" => $paymentIntent
            ]);

        } catch (\Exception $e) {

            Payment::create([
                'enrollment_id' => $enrollment->id,
                'stripe_payment_id' => 'failed',
                'amount' => $course->price,
                'status' => 'failed'
            ]);

            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function withdraw($courseId)
{
    $user = auth()->user();

    $enrollment = Enrollment::where('user_id', $user->id)
        ->where('course_id', $courseId)
        ->whereIn('status', ['paid', 'pending'])
        ->first();

    if (!$enrollment) {
        return response()->json([
            "message" => "Enrollment not found"
        ], 404);
    }

    $enrollment->update([
        'status' => 'cancelled'
    ]);

    return response()->json([
        "message" => "Successfully withdrawn from course"
    ]);
}

}
