<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\EmailExists;
use Laravel\Socialite\Facades\Socialite;
use Kreait\Firebase\Auth\Token\Exception\InvalidToken;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use App\Services\FirebaseRealtimeDatabaseService;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    
}
