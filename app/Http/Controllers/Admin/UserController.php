<?php

namespace LaraDev\Http\Controllers\Admin;

use LaraDev\Support\Cropper;
use LaraDev\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use LaraDev\Http\Controllers\Controller;
use LaraDev\Http\Requests\Admin\User as UserRequest;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Retornando todos os usuários
        $users = User::all();
        return view('admin.users.index', [
            'users' => $users
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function team()
    {
        $user = User::where('admin', 1)->get();
        return view('admin.users.team',[
            'users' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        /** Utilizar este código de vardump quando estiver estudando para ver os campos do formulario */

        /**$user = new User();
        $user->fill($request->all());
        var_dump($user->getAttributes(), $request->all()); */

        $userCreate = User::create($request->all());

        // Se existir e for diferente de vazio
        if(!empty($request->file('cover'))) {
            $userCreate->cover = $request->file('cover')->storeAs('user', str_slug($request->name) . '-' . str_replace('.', '', microtime(true)) . '.' . $request->file('cover')->extension());
            $userCreate->save();
        }

        // Se passar pela validação acima faço a de baixo
        return redirect()->route('admin.users.edit', [
            'users' => $userCreate->id
        ])->with(['color' => 'green', 'message' => 'Cliente cadastrado com sucesso!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Pegando o usuário e mandando para a página de edição
        $user = User::where('id', $id)->first();

       /**  var_dump($user->document, $user->date_of_birth, $user->income, $user->spouse_document, $user-> spouse_date_of_birth, $user->spouse_income, $user->getAttributes());*/

        return view('admin.users.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::where('id', $id)->first();

        $user->setLessorAttribute($request->lessor);
        $user->setLesseeAttribute($request->lessee);
        $user->setAdminAttribute($request->admin);
        $user->setClientAttribute($request->client);

        // Se já existir a mesma imagem irei deletar
        if(!empty($request->file('cover'))) {
            Storage::delete($user->cover);
            Cropper::flush($user->cover);
            $user->cover = '';
        }

        $user->fill($request->all());

        // Se existir e for diferente de vazio
        if(!empty($request->file('cover'))) {
            $user->cover = $request->file('cover')->storeAs('user', str_slug($request->name) . '-' . str_replace('.', '', microtime(true)) . '.' . $request->file('cover')->extension());
        }

        // Se não conseguir salvar
        if(!$user->save()) {
            return redirect()->back()->withInput()->withErrors();
        }
        // Se passar pela validação acima faço a de baixo
        return redirect()->route('admin.users.edit', [
            'users' => $user->id
        ])->with(['color' => 'green', 'message' => 'Cliente atualizado com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
