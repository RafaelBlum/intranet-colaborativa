<?php

namespace App\Http\Controllers;

use App\Cargo;
use App\Comment;
use App\Endereco;
use App\Exports\PedidosExport;
use App\Exports\UsersExport;
use App\Mail\notificationMail;
use App\Notifications\UserNotificacao;
use App\Mail\correioMail;
use App\Questionaire;
use App\Role;
use App\Survey;
use App\Unidade;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;


class UserController extends Controller
{
    /*Annotation: --------------------------------------------------------------
    |1.Lista todos usuário ativos.
    |2.Lista todos usuário inativos.
    |3.Chama tela de cadastro e lista em combo cargos, unidades e funções.
    |4.Realiza o cadastro.
    |5.Mostra usuário selecionado.
    |6.Chama tela de edição e lista em combo cargos, unidades e funções.
    |7.Deleta usuário, avatar personalizado e comentários deste usuário.
    |8.Exporta lista de usuários ativos Excel.
    |9.Exporta lista de usuários inativos Excel.
    |10.Libera pedido de um usuário.
    |11.Nega pedido de usuário.
    |--------------------------------------------------------------------------*/

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $users = User::where('status', '=', 'ativo')->get();
        return view('user.index', compact('users'));
    }

    public function envio(){
        //Mail::to('rafaelblumdigital@gmail.com')->send(new CorreioMail());

        $details = [
            'title'=> 'Confirmação CorporaTix',
            'body'=> 'Seu acesso ao sistema foi aceita!'
        ];

        $novo = User::find(1);
        Mail::to($novo->email)->send(new CorreioMail($details, $novo));

        return redirect()->route('user.index');
    }

    public function pedidos(){
        $users = User::where('status', '!=', 'ativo')->get();
        return view('user.pedidos', compact('users'));
    }

    public function liberaPedido(User $user){
        $autor = Auth::user();
        $details = [
            'title'=> 'Confirmação CorporaTix',
            'body'=> 'Seu acesso ao sistema foi aceita!',
            'user' => $autor->name,
            'pedido'=> 'liberado'
        ];
        //Mail::to('rafaelblum_digital@hotmail.com')->send(new CorreioMail($details, $user));
        Mail::send(new CorreioMail($details, $user));

        notify()->success("Usuário liberado com sucesso!","Success","bottomRight");
        return redirect()->route('user.pedidos');
    }

    public function negarPedido(User $user){
        $autor = Auth::user();

        $details = [
            'title'=> 'Pedido negado - CorporaTix',
            'body'=> 'Seu pedido ao sistema foi negado!',
            'user' => $autor->name,
            'pedido'=> 'bloqueado'
        ];


        //Mail::to($autor->email)->send(new CorreioMail($details, $user));
        Mail::send(new CorreioMail($details, $user));

        notify()->success("Usuário bloqueado com sucesso!","Success","bottomRight");
        return redirect()->route('user.pedidos');
    }

    public function create()
    {
        $cargos = Cargo::all();
        $unidades = Unidade::all();
        $funcoes = Role::all();
        return view('user.create', compact('cargos', 'unidades', 'funcoes'));
    }

    public function store(Request $request)
    {
        try{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->status = 'ativo';

            $cargo = Cargo::find($request->cargo);
            $user->cargo_id = $cargo->id;

            $unidade = Unidade::find($request->unidade);
            $user->unidade_id = $unidade->id;

            $role = Role::find($request->role);
            $user->role_id = $role->id;


            $user->nascimento = $request->nascimento;

            $user->state_civil = $request->stateCivil;
            $user->formacao = $request->formacao;
            $user->fone = $request->fone;
            $user->ramal = $request->ramal;
            $user->descricao = $request->descricao;


            $end = new Endereco();
            $end->street = $request->street;
            $end->number = $request->number;
            $end->city = $request->city;
            $end->state = $request->state;
            $end->cep = $request->cep;
            $end->bairro = $request->bairro;

            if($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $filename = $user->id . "_" . time() . '.' . $avatar->getClientOriginalExtension();
                Image::make($avatar)->resize(300, 300)->save(public_path('public/avatar_users/' . $filename));
                $user->avatar = $filename;
            }

            $user->save();
            //dd('SALVO USER');
            $user->endereco()->save($end);


            notify()->success("Usuário criado com sucesso!","Success","bottomRight");
            return redirect()->route('user.index');

        }catch (\Exception $e){
            dd('ERRO!!'. ' - '. $e);
            if(env('APP_DEBUG')){
                flash($e->getMessage())->warning();
                return redirect()->back();
            }
            notify()->error("Ocorreu um erro ao tentar criar um usuário!","Error","bottomRight");
            return redirect()->back();
        }
    }

    public function show(User $user)
    {
        $questionarios = Questionaire::where('user_id', $user->id)->get();
        $enquetes = Survey::where('email', Auth::user()->email)->get();

        //$myPosts = Post::where('user_id', $user->id)->orderBy('id', 'DESC')->paginate(5); , 'myPosts'
        return view('user.show', compact('user', 'questionarios', 'enquetes'));
    }

    public function edit(User $user)
    {
        $cargos = Cargo::all();
        $unidades = Unidade::all();
        $funcoes = Role::all();
        return view('user.create', compact('user', 'cargos', 'unidades', 'funcoes'));
    }

    //SEM USO - toggle ativa usuario
    public function ativacao(Request $request){
        /*DATA - REQUEST TEM:
         * status: ativo ou inativo
         * member_id: O ID do usuario
         * */

        $user = User::find($request->member_id);
        $user->status = $request->status;
        $user->save();
        notify()->success("Usuário editado com sucesso!","Success","bottomRight");
        return redirect()->route('user.index');
    }

    public function update(Request $request, User $user)
    {
        try{

            $user->name = $request->name;
            $user->email = $request->email;
            if(!empty($request->password)){
                $user->password = Hash::make($request->password);
            }
            $user->status = 'ativo';

            if($request->cargo != null){
                $cargo = Cargo::find($request->cargo);
                $user->cargo_id = $cargo->id;
            }
            if($request->unidade != null ){
                $unidade = Unidade::find($request->unidade);
                $user->unidade_id = $unidade->id;
            }
            if($request->role != null ){
                $role = Role::find($request->role);
                $user->role_id = $role->id;
            }

            $user->nascimento = $request->nascimento;
            $user->state_civil = $request->stateCivil;
            $user->formacao = $request->formacao;
            $user->fone = $request->fone;
            $user->ramal = $request->ramal;
            $user->descricao = $request->descricao;


            $user->endereco->street = $request->street;
            $user->endereco->number = $request->number;
            $user->endereco->bairro = $request->bairro;
            $user->endereco->city = $request->city;
            $user->endereco->state = $request->state;
            $user->endereco->cep = $request->cep;

            //dd($user);

            if($request->hasFile('avatar')){
                $avatar = $request->file('avatar');
                $filename = $user->id . "_" . time() . '.' . $avatar->getClientOriginalExtension();
                Image::make($avatar)->resize(300, 300)->save(public_path('public/avatar_users/' . $filename));

                if($user->avatar !== 'default.jpg'){
                    File::delete(public_path('public/avatar_users/' . $user->avatar));
                }
                $user->avatar =  $filename;
            }

            $user->save();
            $user->endereco()->save($user->endereco);

            notify()->success("Usuário editado com sucesso!","Success","bottomRight");
            return redirect()->route('user.show', compact('user'));

        }catch (\Exception $e){
            if(env('APP_DEBUG')){
                //dd($e->getMessage());
                flash($e->getMessage())->warning();
                return redirect()->back();
            }
            notify()->error("Ocorreu um erro ao tentar editar um usuário!","Error","bottomRight");
            return redirect()->back();
        }

    }

    public function destroy(User $user)
    {
        try{
            $user->load('comments');
            if($user->avatar !== 'default.jpg'){
                File::delete(public_path('public/avatar_users/' . $user->avatar));
            }
            $user->delete();

            notify()->success("Usuário excluido com sucesso!","Success","bottomRight");
            return redirect()->route('user.index');
        }catch (\Exception $e){
            if(env('APP_DEBUG')){
                flash($e->getMessage())->warning();
                return redirect()->back();
            }
            notify()->error("Ocorreu um erro ao tentar excluir um usuário!","Error","bottomRight");
            return redirect()->back();
        }
    }

    public function export(){
        return Excel::download(new UsersExport, 'Lista_usuarios.xlsx');
    }

    public function exportPedidos(){
        return Excel::download(new PedidosExport, 'Lista_pedidos.xlsx');
    }
}
