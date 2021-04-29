<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{

    public function show(Faq $faq)
    {
        return $this->successResponse($faq);
    }

    public function update(Request $request, Faq $faq)
    {
        $validator = Validator::make($request->all(),
            [
                'course_id' => 'required',
                'id' => 'required',
                'answer' => 'required',
                'question' => 'required'
            ]);

        if($validator->fails()){
            return $this->badRequest($validator->errors());
        }else{
            $faq->question =$request->all()['question'];
            $faq->answer =$request->all()['answer'];
            $faq->save();
            return $this->successResponse($faq);

        }

    }

    public function destroy(Faq $faq)
    {
       $faq->delete();
       return $this->successResponse($faq);
    }
}
