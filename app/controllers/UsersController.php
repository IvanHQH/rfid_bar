<?php

    class UsersController extends BaseController {

        function index() {
            $users = User::all();
            $users_types = UserType::all();

            return View::make('users.index', array('users' => $users, 'users_types' => $users_types));
        }

        public function usersDatatables() {
            $users = User::join(DB::raw("(SELECT id user_type_id, type FROM users_types) users_types"), "users.user_type", "=", "users_types.user_type_id") -> select(array('id', 'username', 'email', 'type', 'created_at', 'updated_at'));
            return  Datatables::of($users) -> make();
        }

        public function store($id = 0) {
            $input = Input::All();
            if ($id == 0) {
                $user = new User();
                $user -> password = Hash::make($input['password']);
                $user -> remember_token = '';
            }
            else {
                $user = User::find($id);
                if (!$user) {
                    return App::abort(403, 'Item not found');
                }
            }
            $user-> username = $input['username'];
            $user -> email = $input['email'];
            $user -> user_type = $input['user_type'];
            $user -> save();

            return Response::json($user);
        }

        public function getUser($id) {

            $u = User::join('users_types', 'users.user_type', '=', 'users_types.id') -> select(array('users.id', 'users.username', 'users_types.type', 'users.user_type', 'users.email')) -> where('users.id', '=', $id) -> first();
            if ($u !== null) {
                return Response::json($u);
            }
            return App::abort(403, 'Item not found');
        }

        public function delete($id) {
            $u = User::find($id);
            if ($u) {
                $u -> delete();
            }
            return Response::json(array('ok' => 'ok'));
        }

        public function usersCSV() {
            $columns=array('id', 'username', 'email', 'created_at', 'updated_at');
            $headers=array('id', 'username', 'email', 'created_at', 'updated_at');
            CSVGenerate::sendCSV($columns, $headers, "users");
        }
    }
