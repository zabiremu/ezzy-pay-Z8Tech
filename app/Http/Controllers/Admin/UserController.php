<?php

namespace App\Http\Controllers\Admin;

use App\Models\CEO;
use App\Models\COE;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Setting;
use App\Models\WithDraw;
use App\Models\IntialCEO;
use App\Models\IntialCOE;
use App\Models\SendMoney;
use App\Models\EzzyLeader;
use App\Models\EzzyMember;
use App\Models\EzzyReward;
use App\Models\EzzyManager;
use Illuminate\Http\Request;
use App\Models\EzzyDirectory;
use App\Models\EzzyExecutive;
use App\Models\LevelOneToFifteen;
use App\Models\InitialStepForRank;
use App\Models\IntialEzzyDirectory;
use App\Models\SendMoneyForFriends;
use App\Http\Controllers\Controller;
use App\Models\DailyBonusTime;
use App\Models\DateTime;
use Illuminate\Support\Facades\Auth;
use App\Models\InitialStepForEzzyLeader;
use App\Models\InitialStepForEzzyManger;
use App\Models\InitailStepForEzzyExecutive;
use App\Models\ProjectDateTime;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::where('is_users', 1)
            ->where('nid_verified', 1)
            ->latest()
            ->get();
        return view('admin.users.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getAjaxUser(Request $request)
    {
        $user_name = $request->key1;
        $user = User::where('username', $user_name)->first();

        if ($user) {
            $firstName = $user->first_name;
            $lastName = $user->last_name;
            $name = $firstName . ' ' . $lastName;
            return response()->json($name);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create()
    {
    }

    /**
     * Display the specified resource.
     */
    public function affilateIndex()
    {
        // $users= User::with('InitialStepForRank','InitialStepForEzzyLeader','InitialStepForEzzyManger','InitailStepForEzzyExecutive','InitailStepForEzzyExecutive','IntialEzzyDirectory', 'IntialCOE', 'IntialCEO' ,'COE','CEO' ,'EzzyDirectory','EzzyExecutive','EzzyManager','EzzyManager','EzzyLeader','EzzyMember')->latest()->get();
        $user = Auth::user()->username;
        $affilateusers = User::where('sponsor', $user)->get();
        return view('users.affilate.index', compact('affilateusers'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    //  nestedMember
    public function nestedMember($id)
    {
        $user = User::find($id);
        $username = $user->username;
        $nested_users = User::where('sponsor', $username)->get();
        return view('users.affilate.nested', compact('nested_users'));
    }

    public function edit(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            if ($request->isMethod('PUT')) {
                $request->validate([
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'email' => 'required|email',
                    'phone_no' => 'required|numeric|digits:11',
                    'country' => 'nullable',
                    'address' => 'nullable',
                    't_pin' => 'required',
                    'sponsor' => 'required',
                    'rank' => 'nullable',
                    'bank' => 'nullable',
                    'nid_verified' => 'required',
                ]);
                $user->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone_no' => $request->phone_no,
                    't_pin' => $request->t_pin,
                    'sponsor' => $request->sponsor,
                    'rank' => $request->rank,
                    'country' => $request->country,
                    'address' => $request->address,
                    'bank' => $request->bank,
                    'nid_verified' => $request->nid_verified,
                ]);
                if ($request->nid_verified == 1) {
                    $user->update([
                        'is_approved' => 1,
                    ]);
                } else {
                    $user->update([
                        'is_approved' => 0,
                    ]);
                }
                return redirect()
                    ->back()
                    ->with('success', 'User Details succesfully updated');
            } else {
                return view('admin.users.edit', compact('user'));
            }
        } else {
            return redirect()
                ->back()
                ->with('errors', 'Something went wrong');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function registration($username)
    {
        $user = User::where('username', $username)->first();
        return view('auth.referLinkRegister', compact('user'));
    }

    // unverified_users
    public function unverified_users()
    {
        $data = User::where('nid_verified', 0)
            ->latest()
            ->get();
        return view('admin.users.unverified-users', compact('data'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function userDashboard()
    {
        $wallet = Wallet::where('user_id', Auth::user()->id)->first();
        $level1 = LevelOneToFifteen::where('level_1', '!=', null)
            ->get(['id', 'level_1'])
            ->count();
        $level2 = LevelOneToFifteen::where('level_2', '!=', null)
            ->get(['id', 'level_2'])
            ->count();
        $level3 = LevelOneToFifteen::where('level_3', '!=', null)
            ->get(['id', 'level_3'])
            ->count();
        $level4 = LevelOneToFifteen::where('level_4', '!=', null)
            ->get(['id', 'level_4'])
            ->count();
        $level5 = LevelOneToFifteen::where('level_5', '!=', null)
            ->get(['id', 'level_5'])
            ->count();
        $level6 = LevelOneToFifteen::where('level_6', '!=', null)
            ->get(['id', 'level_6'])
            ->count();
        $level7 = LevelOneToFifteen::where('level_7', '!=', null)
            ->get(['id', 'level_7'])
            ->count();
        $level8 = LevelOneToFifteen::where('level_8', '!=', null)
            ->get(['id', 'level_8'])
            ->count();
        $level9 = LevelOneToFifteen::where('level_9', '!=', null)
            ->get(['id', 'level_9'])
            ->count();
        $level10 = LevelOneToFifteen::where('level_10', '!=', null)
            ->get(['id', 'level_10'])
            ->count();
        $level11 = LevelOneToFifteen::where('level_11', '!=', null)
            ->get(['id', 'level_11'])
            ->count();
        $level12 = LevelOneToFifteen::where('level_12', '!=', null)
            ->get(['id', 'level_12'])
            ->count();
        $level13 = LevelOneToFifteen::where('level_13', '!=', null)
            ->get(['id', 'level_13'])
            ->count();
        $level14 = LevelOneToFifteen::where('level_14', '!=', null)
            ->get(['id', 'level_14'])
            ->count();
        $level15 = LevelOneToFifteen::where('level_15', '!=', null)
            ->get(['id', 'level_15'])
            ->count();

        $totalSend = SendMoneyForFriends::where('master_id', Auth::user()->id)
            ->select('send_amount')
            ->sum('send_amount');
        $totalReceive = SendMoneyForFriends::where('user_id', Auth::user()->id)
            ->select('send_amount')
            ->sum('send_amount');
        $addFund = SendMoney::where('user_id', Auth::user()->id)
            ->select('send_amount')
            ->sum('send_amount');
        $addFund = SendMoney::where('user_id', Auth::user()->id)
            ->select('send_amount')
            ->sum('send_amount');
        $pendingWithDraw = WithDraw::where('user_id', Auth::user()->id)
            ->where('status', 0)
            ->select('amount')
            ->sum('amount');
        $accpectWithDraw = WithDraw::where('user_id', Auth::user()->id)
            ->where('status', 1)
            ->select('amount')
            ->sum('amount');
        $user = User::where('id', Auth::user()->id)
            ->where('is_approved', 1)
            ->first();
        $referLink = config('app.url') . 'registration/' . Auth::user()->username;

        // Project date duration
        $now = Carbon::now()->format('Y:m:d H:i:s');
        $project_duration = ProjectDateTime::where('user_id', Auth::user()->id)->first();

        if ($project_duration) {
            $project_duration->update([
                'current_date_time' => $now,
            ]);

            $start = Carbon::parse($project_duration->project_date_start);
            $current = Carbon::parse($project_duration->current_date_time);
            $end = Carbon::parse($project_duration->project_date_end);

            $days = $end->diffInDays($start);
            $days_left = $end->diffInDays($current);

            if ($end->isPast()) {
                $project_duration->update([
                    'status' => 0,
                ]);
            }
        } else {
            $days = 0;
            $days_left = 0;
        }

        return view('users.dashboard', compact('wallet', 'level1', 'level2', 'level3', 'level4', 'level5', 'level6', 'level7', 'level8', 'level9', 'level10', 'level11', 'level12', 'level13', 'level14', 'level15', 'totalSend', 'totalReceive', 'addFund', 'pendingWithDraw', 'accpectWithDraw', 'user', 'referLink', 'project_duration', 'days', 'days_left'));
    }

    public function activate()
    {
        $user_id = Auth::user()->id;
        $settings = Setting::first();

        $project_date_time = ProjectDateTime::where('user_id', $user_id)->first();
        if ($project_date_time) {
            if ($project_date_time->status == 1) {
                return redirect()
                    ->route('users.dashboard')
                    ->with('errors', 'Your account is already activated');
            }
        } else {
            $wallet = Wallet::where('user_id', $user_id)->first();
            if ($wallet) {
                $user = User::where('id', $user_id)->first();
                $user->is_approved = 1;
                $user->save();

                $wallet->user_id = $user->id;
                if ($wallet->booking_wallet >= $settings->registration) {
                    $wallet->booking_wallet = $wallet->booking_wallet - $settings->registration;
                    $wallet->is_approved = 1;
                    $wallet->save();

                    // project date time store
                    $now = Carbon::now()->format('Y:m:d H:i:s');
                    $endday = Carbon::today()
                        ->addYear()
                        ->format('Y:m:d H:i:s');

                    $project_date_time = ProjectDateTime::where('user_id', $user_id)->first();
                    if ($project_date_time) {
                        $project_date_time->update([
                            'project_date_begin' => $now,
                            'current_date_time' => $now,
                            'project_date_end' => $endday,
                            'status' => 1,
                        ]);
                    } else {
                        ProjectDateTime::create([
                            'user_id' => $user_id,
                            'project_date_begin' => $now,
                            'current_date_time' => $now,
                            'project_date_end' => $endday,
                            'status' => 1,
                        ]);
                    }
                } else {
                    return back()->with('errors', 'Insufficient activation balance. minimum balance will' . $settings->registration);
                }

                if ($wallet) {
                    $master_user_id = User::where('username', $user->sponsor)->first();
                    if ($master_user_id->is_approved == 1) {
                        if (isset($master_user_id)) {
                            $wallet = Wallet::where('user_id', $master_user_id->id)->first();
                            if (isset($wallet)) {
                                $wallet->affiliate_income = 50 + $wallet->affiliate_income;
                                $wallet->save();
                            }
                        }
                    } else {
                        $wallet = Wallet::where('user_id', 1)->first();
                        $wallet->my_wallet = 50 + $wallet->affiliate_income;
                        $wallet->save();
                    }
                }

                if ($wallet) {
                    if ($user->sponsor) {
                        $master = User::where('username', $user->sponsor)->first();
                        $ezzyMmeber = EzzyMember::where('user_id', $master->id)->first();
                        if (isset($ezzyMmeber)) {
                            if (isset($master)) {
                                $levelOneToFifteen = new LevelOneToFifteen();
                                $levelOneToFifteen->level_1 = $ezzyMmeber->user_id;
                                $levelOneToFifteen->save();
                            }
                            $master = User::where('id', $levelOneToFifteen->level_1)->first();
                            if (isset($master->sponsor)) {
                                $master = User::where('username', $master->sponsor)->first();
                                if ($levelOneToFifteen) {
                                    $levelOneToFifteen = LevelOneToFifteen::where('level_1', $levelOneToFifteen->level_1)->first();

                                    $levelOneToFifteen->level_2 = $master->id;
                                    $levelOneToFifteen->save();
                                }
                            }
                            $master = User::where('id', $levelOneToFifteen->level_2)->first();
                            if (isset($master->sponsor)) {
                                $master = User::where('username', $master->sponsor)->first();
                                if ($levelOneToFifteen) {
                                    $levelOneToFifteen = LevelOneToFifteen::where('level_1', $levelOneToFifteen->level_1)->first();
                                    $levelOneToFifteen->level_3 = $master->id;
                                    $levelOneToFifteen->save();
                                }
                            }
                            $master = User::where('id', $levelOneToFifteen->level_3)->first();
                            if (isset($master->sponsor)) {
                                $master = User::where('username', $master->sponsor)->first();
                                if ($levelOneToFifteen) {
                                    $levelOneToFifteen = LevelOneToFifteen::where('level_1', $levelOneToFifteen->level_1)->first();
                                    $levelOneToFifteen->level_4 = $master->id;
                                    $levelOneToFifteen->save();
                                }
                            }
                            $master = User::where('id', $levelOneToFifteen->level_4)->first();
                            if (isset($master->sponsor)) {
                                $master = User::where('username', $master->sponsor)->first();
                                if ($levelOneToFifteen) {
                                    $levelOneToFifteen = LevelOneToFifteen::where('level_1', $levelOneToFifteen->level_1)->first();
                                    $levelOneToFifteen->level_5 = $master->id;
                                    $levelOneToFifteen->save();
                                }
                            }
                            $master = User::where('id', $levelOneToFifteen->level_5)->first();
                            if (isset($master->sponsor)) {
                                $master = User::where('username', $master->sponsor)->first();
                                if ($levelOneToFifteen) {
                                    $levelOneToFifteen = LevelOneToFifteen::where('level_1', $levelOneToFifteen->level_1)->first();
                                    $levelOneToFifteen->level_6 = $master->id;
                                    $levelOneToFifteen->save();
                                }
                            }
                            $master = User::where('id', $levelOneToFifteen->level_5)->first();
                            if (isset($master->sponsor)) {
                                $master = User::where('username', $master->sponsor)->first();
                                if ($levelOneToFifteen) {
                                    $levelOneToFifteen = LevelOneToFifteen::where('level_1', $levelOneToFifteen->level_1)->first();
                                    $levelOneToFifteen->level_6 = $master->id;
                                    $levelOneToFifteen->save();
                                }
                            }
                            $master = User::where('id', $levelOneToFifteen->level_6)->first();
                            if (isset($master->sponsor)) {
                                $master = User::where('username', $master->sponsor)->first();
                                if ($levelOneToFifteen) {
                                    $levelOneToFifteen = LevelOneToFifteen::where('level_1', $levelOneToFifteen->level_1)->first();
                                    $levelOneToFifteen->level_7 = $master->id;
                                    $levelOneToFifteen->save();
                                }
                            }
                            $master = User::where('id', $levelOneToFifteen->level_7)->first();
                            if (isset($master->sponsor)) {
                                $master = User::where('username', $master->sponsor)->first();
                                if ($levelOneToFifteen) {
                                    $levelOneToFifteen = LevelOneToFifteen::where('level_1', $levelOneToFifteen->level_1)->first();
                                    $levelOneToFifteen->level_8 = $master->id;
                                    $levelOneToFifteen->save();
                                }
                            }
                            $master = User::where('id', $levelOneToFifteen->level_8)->first();
                            if (isset($master->sponsor)) {
                                $master = User::where('username', $master->sponsor)->first();
                                if ($levelOneToFifteen) {
                                    $levelOneToFifteen = LevelOneToFifteen::where('level_1', $levelOneToFifteen->level_1)->first();
                                    $levelOneToFifteen->level_9 = $master->id;
                                    $levelOneToFifteen->save();
                                }
                            }
                            $master = User::where('id', $levelOneToFifteen->level_9)->first();
                            if (isset($master->sponsor)) {
                                $master = User::where('username', $master->sponsor)->first();
                                if ($levelOneToFifteen) {
                                    $levelOneToFifteen = LevelOneToFifteen::where('level_1', $levelOneToFifteen->level_1)->first();
                                    $levelOneToFifteen->level_10 = $master->id;
                                    $levelOneToFifteen->save();
                                }
                            }
                            $master = User::where('id', $levelOneToFifteen->level_10)->first();
                            if (isset($master->sponsor)) {
                                $master = User::where('username', $master->sponsor)->first();
                                if ($levelOneToFifteen) {
                                    $levelOneToFifteen = LevelOneToFifteen::where('level_1', $levelOneToFifteen->level_1)->first();
                                    $levelOneToFifteen->level_11 = $master->id;
                                    $levelOneToFifteen->save();
                                }
                            }
                            $master = User::where('id', $levelOneToFifteen->level_11)->first();
                            if (isset($master->sponsor)) {
                                $master = User::where('username', $master->sponsor)->first();
                                if ($levelOneToFifteen) {
                                    $levelOneToFifteen = LevelOneToFifteen::where('level_1', $levelOneToFifteen->level_1)->first();
                                    $levelOneToFifteen->level_12 = $master->id;
                                    $levelOneToFifteen->save();
                                }
                            }
                            $master = User::where('id', $levelOneToFifteen->level_12)->first();
                            if (isset($master->sponsor)) {
                                $master = User::where('username', $master->sponsor)->first();
                                if ($levelOneToFifteen) {
                                    $levelOneToFifteen = LevelOneToFifteen::where('level_1', $levelOneToFifteen->level_1)->first();
                                    $levelOneToFifteen->level_13 = $master->id;
                                    $levelOneToFifteen->save();
                                }
                            }
                            $master = User::where('id', $levelOneToFifteen->level_14)->first();
                            if (isset($master->sponsor)) {
                                $master = User::where('username', $master->sponsor)->first();
                                if ($levelOneToFifteen) {
                                    $levelOneToFifteen = LevelOneToFifteen::where('level_1', $levelOneToFifteen->level_1)->first();
                                    $levelOneToFifteen->level_15 = $master->id;
                                    $levelOneToFifteen->save();
                                }
                            }
                        }
                    }
                }

                if ($wallet) {
                    $sponsor_level_1 = null;
                    if (Auth::user()->sponsor) {
                        $sponsor_level_1 = User::where('username', $user->sponsor)->first();
                        if ($sponsor_level_1) {
                            $walletOne = Wallet::where('user_id', $sponsor_level_1->id)->first();
                            if ($walletOne) {
                                $walletOne->level_bonus = 5 + $walletOne->level_bonus;
                                $walletOne->save();
                            }
                        }
                    }
                    $sponsor_level_2 = null;
                    if ($sponsor_level_1) {
                        $sponsor_level_2 = User::where('username', $sponsor_level_1->sponsor)->first();
                        if ($sponsor_level_2) {
                            $walletTwo = Wallet::where('user_id', $sponsor_level_2->id)->first();
                            if ($walletTwo) {
                                $walletTwo->level_bonus = 5 + $walletTwo->level_bonus;
                                $walletTwo->save();
                            }
                        }
                    }
                    $sponsor_level_3 = null;
                    if ($sponsor_level_2) {
                        $sponsor_level_3 = User::where('username', $sponsor_level_2->sponsor)->first();
                        if ($sponsor_level_3) {
                            $walletThree = Wallet::where('user_id', $sponsor_level_3->id)->first();
                            if ($walletThree) {
                                $walletThree->level_bonus = 5 + $walletThree->level_bonus;
                                $walletThree->save();
                            }
                        }
                    }
                    $sponsor_level_4 = null;
                    if ($sponsor_level_3) {
                        $sponsor_level_4 = User::where('username', $sponsor_level_3->sponsor)->first();
                        if ($sponsor_level_4) {
                            $walletFour = Wallet::where('user_id', $sponsor_level_4->id)->first();
                            if ($walletFour) {
                                $walletFour->level_bonus = 5 + $walletFour->level_bonus;
                                $walletFour->save();
                            }
                        }
                    }
                    $sponsor_level_5 = null;
                    if ($sponsor_level_4) {
                        $sponsor_level_5 = User::where('username', $sponsor_level_4->sponsor)->first();
                        if ($sponsor_level_5) {
                            $walletFive = Wallet::where('user_id', $sponsor_level_5->id)->first();
                            if ($walletFive) {
                                $walletFive->level_bonus = 5 + $walletFive->level_bonus;
                                $walletFive->save();
                            }
                        }
                    }
                    $sponsor_level_6 = null;
                    if ($sponsor_level_5) {
                        $sponsor_level_6 = User::where('username', $sponsor_level_5->sponsor)->first();
                        if ($sponsor_level_6) {
                            $walletSix = Wallet::where('user_id', $sponsor_level_6->id)->first();
                            if ($walletSix) {
                                $walletSix->level_bonus = 5 + $walletSix->level_bonus;
                                $walletSix->save();
                            }
                        }
                    }
                    $sponsor_level_7 = null;
                    if ($sponsor_level_6) {
                        $sponsor_level_7 = User::where('username', $sponsor_level_6->sponsor)->first();
                        if ($sponsor_level_7) {
                            $walletSeven = Wallet::where('user_id', $sponsor_level_7->id)->first();
                            if ($walletSeven) {
                                $walletSeven->level_bonus = 5 + $walletSeven->level_bonus;
                                $walletSeven->save();
                            }
                        }
                    }
                    $sponsor_level_8 = null;
                    if ($sponsor_level_7) {
                        $sponsor_level_8 = User::where('username', $sponsor_level_7->sponsor)->first();
                        if ($sponsor_level_8 !== null) {
                            $walletEight = Wallet::where('user_id', $sponsor_level_8->id)->first();
                            if ($walletEight) {
                                $walletEight->level_bonus = 5 + $walletEight->level_bonus;
                                $walletEight->save();
                            }
                        }
                    }
                    $sponsor_level_9 = null;
                    if ($sponsor_level_8) {
                        $sponsor_level_9 = User::where('username', $sponsor_level_8->sponsor)->first();
                        if ($sponsor_level_9) {
                            $walletNine = Wallet::where('user_id', $sponsor_level_9->id)->first();
                            if ($walletNine) {
                                $walletNine->level_bonus = 5 + $walletNine->level_bonus;
                                $walletNine->save();
                            }
                        }
                    }
                    $sponsor_level_10 = null;
                    if ($sponsor_level_9) {
                        $sponsor_level_10 = User::where('username', $sponsor_level_9->sponsor)->first();
                        if ($sponsor_level_10) {
                            $walletTen = Wallet::where('user_id', $sponsor_level_10->id)->first();
                            if ($walletTen) {
                                $walletTen->level_bonus = 5 + $walletTen->level_bonus;
                                $walletTen->save();
                            }
                        }
                    }
                    $sponsor_level_11 = null;
                    if ($sponsor_level_10) {
                        $sponsor_level_11 = User::where('username', $sponsor_level_10->sponsor)->first();
                        if ($sponsor_level_11 !== null) {
                            $walletEleven = Wallet::where('user_id', $sponsor_level_11->id)->first();
                            if ($walletEleven) {
                                $walletEleven->level_bonus = 5 + $walletEleven->level_bonus;
                                $walletEleven->save();
                            }
                        }
                    }
                    $sponsor_level_12 = null;
                    if ($sponsor_level_11) {
                        $sponsor_level_12 = User::where('username', $sponsor_level_11->sponsor)->first();
                        if ($sponsor_level_12) {
                            $walletTwelve = Wallet::where('user_id', $sponsor_level_12->id)->first();
                            if ($walletTwelve) {
                                $walletTwelve->level_bonus = 5 + $walletTwelve->level_bonus;
                                $walletTwelve->save();
                            }
                        }
                    }
                    $sponsor_level_13 = null;
                    if ($sponsor_level_12) {
                        $sponsor_level_13 = User::where('username', $sponsor_level_12->sponsor)->first();
                        if ($sponsor_level_13) {
                            $walletThirteen = Wallet::where('user_id', $sponsor_level_2->id)->first();
                            $walletThirteen->level_bonus = 5 + $wallet->level_bonus;
                            $walletThirteen->save();
                        }
                    }
                    $sponsor_level_14 = null;
                    if ($sponsor_level_13) {
                        $sponsor_level_14 = User::where('username', $sponsor_level_13->sponsor)->first();
                        if ($sponsor_level_14) {
                            $walletFourteen = Wallet::where('user_id', $sponsor_level_14->id)->first();
                            if ($walletFourteen) {
                                $walletFourteen->level_bonus = 5 + $walletFourteen->level_bonus;
                                $walletFourteen->save();
                            }
                        }
                    }
                    $sponsor_level_15 = null;
                    if ($sponsor_level_14) {
                        $sponsor_level_15 = User::where('username', $sponsor_level_14->sponsor)->first();
                        if ($sponsor_level_15) {
                            $walletFifteen = Wallet::where('user_id', $sponsor_level_15->id)->first();
                            if ($walletFifteen) {
                                $walletFifteen->level_bonus = 5 + $walletFifteen->level_bonus;
                                $walletFifteen->save();
                            }
                        }
                    }
                }

                if ($wallet) {
                   /////////////////////////////////////////// rank code start form here /////////////////////////////////////////////////
                   ////////////////////////////////////////// rank code end form here //////////////////////////////////////////////////
                }
            } else {
                return back()->with('errors', 'Insufficient activation balance. minimum balance will ' . $settings->registration);
            }
            return redirect()
                ->route('users.dashboard')
                ->with('success', 'Your account succesfull activated for 365 days.');
        }
    }
}
