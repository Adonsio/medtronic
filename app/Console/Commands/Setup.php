<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class Setup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = new User();
        $user->firstname = 'Admin';
        $user->lastname = 'Admin';
        $user->fullname = 'Admin';
        $user->login_identifier = 'Admin';
        $user->email = 'admin@admin.com';
        $user->password = Hash::make('TYFe3_HaPDnt');

        Role::create(['name' => 'superuser']);
        Role::create(['name' => 'people']);

        $user->assignRole('superuser');
        $user->department = 'none';
        $user->site = 'none';
        $user->save();


        echo "Admin User created, you may now login with \n Login Identifier: Admin \n Password: TYFe3_HaPDnt";
            /*
             *             'login_identifier' => $request->login_identifier,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'fullname' => $request->firstname . ' ' . $request->lastname,
            'password' => Hash::make($request->password),
             *
             *
             *
             */
        return "setup";
    }
}
