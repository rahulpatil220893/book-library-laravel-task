<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Book;
use App\models\Rent_book;

use Validator;
use DB;
class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $response_status=200;
        $status=true;
        $response['book'] = Book::paginate(5);
        return response()->json(array('status'=>$status,'response' => $response),$response_status);
    }

    public function data($id){
        $response_status=200;
        $status=true;
        $response['book'] = Book::find($id);
        return response()->json(array('status'=>$status,'response' => $response),$response_status);
    }

    public function add(Request $request){
        $status=false;
        $response_status=401;
        $response=array();

        $rules=[ 
            'book_name' => ['required','unique:books'],
            'author' => ['required','alpha'],
            'cover_image' => ['required']
        ];

        $validation = Validator::make($request->all(),$rules);
        if ($validation->fails()) {
            $response['error'] = $validation->errors();
        }else{
            $save_data=[
                'book_name' => $request->book_name,
                'author' => $request->author,
                'cover_image' => $request->cover_image
            ];
            $book_data=Book::create($save_data);
            if($book_data){
                $response_status=200;
                $status=true;
                $response['message'] = 'Data added successfully';
                $response['data'] = $book_data;
            }else{
                $response['message'] = 'Data add failed';
            }
        }
        return response()->json(array('status'=>$status,'response' => $response),$response_status);
    }

    public function update(Request $request,$id){
        $status=false;
        $response_status=401;
        $response=array();

        $save_book_data=Book::find($id);
        if($save_book_data){
            $rules=[ 
                'book_name' => ['required',"unique:books,book_name,".$save_book_data->b_id.",b_id"],
                'author' => ['required','alpha'],
                'cover_image' => ['required']
            ];

            $validation = Validator::make($request->all(),$rules);
            if ($validation->fails()) {
                $response['error'] = $validation->errors();
            }else{

                $save_book_data->book_name = $request->book_name;
                $save_book_data->author = $request->author;
                $save_book_data->cover_image = $request->cover_image;
                $save_book_data->save();

                $response_status=200;
                $status=true;
                $response['message'] = 'Data update successfully';
                $response['data'] = $save_book_data;

            }
        }else{
            $response['message'] = 'Data update failed';
        }
        return response()->json(array('status'=>$status,'response' => $response),$response_status);
    }

    public function delete($id){
        $status=false;
        $response_status=401;
        $response=array();

        $book_data = Book::find($id);
        if($book_data){
            $book_data->delete();

            $response_status=200;
            $status=true;
            $response['message'] = 'Data delete successfully';
            $response['data'] = $book_data;
        }else{
            $response['message'] = 'Data delete fail';
        }
        return response()->json(array('status'=>$status,'response' => $response),$response_status);
    }

    public function rentBook(Request $request){
        $status=false;
        $response_status=401;
        $response=array();

        $book_data = Book::find($request->book_id);
        if($book_data){
            $user_data=auth()->user();
            $check_rent_book = Rent_book::where('book_id',$request->book_id)->where('user_id',$user_data->u_id)->get()->first();
            if(!$check_rent_book){
                $save_data=[
                    'book_id' => $request->book_id,
                    'user_id' => $user_data->u_id
                ];
                $book_data=Rent_book::create($save_data);

                $response_status=200;
                $status=true;
                $response['message'] = 'Book rented successfully';
                $response['data'] = $book_data;
            }else{
                $response['message'] = 'You have already rented book successfully.';
            }
        }else{
            $response['message'] = 'Rent a book fail.';
        }
        return response()->json(array('status'=>$status,'response' => $response),$response_status);
    }

    public function returnBook($id){
        $status=false;
        $response_status=401;
        $response=array();

        $rent_book_data = Rent_book::find($id);
        if($rent_book_data){
            $rent_book_data->delete();
            
            $response_status=200;
            $status=true;
            $response['message'] = 'Book Return successfully';
            $response['data'] = $rent_book_data;
        }else{
            $response['message'] = 'Return a book fail';
        }
        return response()->json(array('status'=>$status,'response' => $response),$response_status);
    }

    public function rentedBookList(){
        $user_data=auth()->user();

        $response_status=200;
        $status=true;
        $response['message'] = 'Book Return successfully';
        $response['data'] = Rent_book::join('books', 'books.b_id', '=', 'Rent_book.book_id')
        ->where('Rent_book.user_id',$user_data->u_id)->get(['Rent_book.id','Rent_book.book_id','books.book_name','books.author']);
        return response()->json(array('status'=>$status,'response' => $response),$response_status);
    }
}
