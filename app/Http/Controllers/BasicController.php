<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\BurialCouncil;
use App\Models\ContactUpdate;
use App\Models\Faq;
use App\Models\FaqUpdate;
use App\Models\FivePillar;
use App\Models\HMBC;
use App\Models\HomeUpdate;
use App\Models\NewMember;
use App\Models\PaymentInfo;
use App\Models\PaymentStatus;
use App\Models\RulesRegulation;
use App\Models\RulesRegulationUpdate;
use App\Models\WebSetting;
use Illuminate\Http\Request;

class BasicController extends Controller
{
    //index page
    public function index()
    {
        $home = HomeUpdate::first();
        $pillar = FivePillar::where('is_active', '1')->get();
        return view('web.index', compact('home', 'pillar'));
    }
    //FAQ
    public function faq()
    {
        $update = FaqUpdate::first();
        $faq = Faq::where('is_active', '1')->get()->all();
        return view('web.faq', compact('update', 'faq'));
    }
    public function rules()
    {
        $rules = RulesRegulation::where('is_active', 1)
            ->orderByDesc('id')
            ->get();

        $update = RulesRegulationUpdate::first();
        return view('web.rules', compact('rules','update'));
    }
    public function contactus()
    {
        $websetting = WebSetting::first();
        $contact = ContactUpdate::first();
        return view('web.contact', compact('websetting', 'contact'));
    }

    public function hmbc()
    {
        $hmbc = HMBC::first();
        return view('web.hmbc', compact('hmbc'));
    }
    public function council()
    {
        $council = BurialCouncil::first();
        return view('web.Burial', compact('council'));
    }
    public function about()
    {
        $about = AboutUs::first();
        return view('web.aboutus', compact('about'));
    }
    public function newmember()
    {
        $rules = RulesRegulation::where('is_active', 1)
            ->orderByRaw("CASE WHEN title = 'New Members' THEN 0 ELSE 1 END")
            ->orderByDesc('id')
            ->get();

        $newmember = NewMember::first();
        return view('web.newmember', compact('rules', 'newmember'));
    }
    public function payment()
    {
        // Fetch the latest PaymentInfo record
        $paymentInfo = PaymentInfo::orderByDesc('id')->first();

        return view('web.payementinfo', compact('paymentInfo'));
    }
    public function paymentsucces()
    {
        // Fetch the latest PaymentStatus record
        $paymentStatus = PaymentStatus::orderByDesc('id')->first();

        return view('web.paymentstatus', compact('paymentStatus'));
    }

    /**
     * Get transaction progress for a member by unique ID and year
     */
    public function getTransactionProgress($uniqueId, $year)
    {
        try {
            // Find user by unique ID (case-insensitive)
            $user = \App\Models\User::whereRaw('LOWER(unique_id) = ?', [strtolower($uniqueId)])->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Member not found with this Unique ID'
                ], 404);
            }

            // Get annual fee setting for the year
            $annualFeeSetting = \App\Models\MembershipFeeSetting::where(function($query) use ($user) {
                    $query->where('member_type', $user->member_type ?? 'annual_fee')
                          ->orWhere('member_type', 'annual_fee');
                })
                ->where('year', $year)
                ->first();

            $annualFeeAmount = $annualFeeSetting ? $annualFeeSetting->amount : 0;

            // Get transactions for the user in the specified year
            $transactions = \App\Models\Transaction::where('user_id', $user->id)
                ->where('flag_status', 1) // Only verified transactions
                ->whereYear('date', $year)
                ->get();

            $paidAmount = $transactions->sum('amount');
            $progressPercent = $annualFeeAmount > 0 ? min(100, ($paidAmount / $annualFeeAmount) * 100) : 0;
            $isVerified = $progressPercent >= 100 && $annualFeeAmount > 0;

            return response()->json([
                'success' => true,
                'progress' => [
                    'memberName' => $user->name,
                    'uniqueId' => $user->unique_id,
                    'year' => $year,
                    'annualFee' => (float) $annualFeeAmount,
                    'paidAmount' => $paidAmount,
                    'progressPercent' => $progressPercent,
                    'isVerified' => $isVerified,
                    'transactionCount' => $transactions->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching progress'
            ], 500);
        }
    }
}
