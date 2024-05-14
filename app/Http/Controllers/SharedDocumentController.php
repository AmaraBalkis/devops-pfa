<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\SharedDocument;

class SharedDocumentController extends Controller
{
/**
 * @OA\Get(
 *      path="/api/sharedDocuemnt",
 *      operationId="getsharedDocuemntList",
 *      tags={"Shared Document"},
 *      summary="Get list of sharedDocuemnt",
 *      description="Returns list of sharedDocuemnt",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation"
 *       ),
 *      @OA\Response(
 *          response=404,
 *          description="No sharedDocuemnt found"
 *      )
 * )
 */    
    public function index()
    {
        $shared_document = SharedDocument::all();
        if ($shared_document->count()>0) {
            return response()->json(['status' => 200,
                                      'shared_document'=> $shared_document ], 200);
        }else{
            return response()->json(['status' => 404,
                                     'message' => __('messages.shared_document').__('messages.not_found')], 404);
        }
    }

/**
 * @OA\Post(
 *      path="/api/sharedDocument",
 *      operationId="storeSharedDocument",
 *      tags={"Shared Document"},
 *      summary="Store a new shared document",
 *      description="Creates a new shared document record",
 *      @OA\RequestBody(
 *          required=true,
 *          description="Data for creating a new shared document",
 *          @OA\JsonContent(
 *              required={"privilege", "doctor_id", "document_id"},
 *              @OA\Property(property="privilege", type="string", enum={"-", "r", "w", "rw"}, description="Privilege level"),
 *              @OA\Property(property="doctor_id", type="integer", format="int64", description="ID of the doctor"),
 *              @OA\Property(property="document_id", type="integer", format="int64", description="ID of the document"),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Shared document created successfully",
 *      ),
 *      @OA\Response(
 *          response=409,
 *          description="Shared document already exists",
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Internal server error",
 *      )
 * )
 */
    public function store(Request $request)
    {
        $validator= validator::make($request->all(),[ 
            'privilege' => 'required|in:-,r,w,rw', 
            'doctor_id' => 'required|numeric|min:0',
            'document_id' => 'required|numeric|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'erreur' => $validator->messages()],422);
        }
        else{
            $exist=SharedDocument::where('doctor_id',$request->doctor_id)->where('document_id',$request->document_id)->count()!=0;
            if ($exist!=0){
                    return response()->json([
                        'status' => 409, 
                        'message' => __('messages.shared_document').__('messages.exist')], 409);
            }
            
            $shared_document = SharedDocument::create($request->all());
            if($shared_document){
                return response()->json([
                    'status' => 200,
                    'message' => __('messages.shared_document').__('messages.created')],200);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => __('messages.shared_document').__('messages.erreur')],500);
            }
        }
    }
/**
 * @OA\Get(
 *      path="/api/sharedDocument/{id}",
 *      operationId="getSharedDocumentById",
 *      tags={"Shared Document"},
 *      summary="Get shared document by ID",
 *      description="Retrieves a shared document by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the shared document",
 *          required=true,
 *          @OA\Schema(
 *              type="string",
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Shared document found",
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Shared document not found",
 *      )
 * )
 */
    public function show(string $id)
    {
        $shared_document = SharedDocument::find($id);
        if ($shared_document) {
            return response()->json([
                'status' => 200,
                'shared_document' => $shared_document],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => __('messages.shared_document').__('messages.not_found')],404); 
        }  
    }

/**
 * @OA\Put(
 *      path="/api/sharedDocument/{id}",
 *      operationId="updateSharedDocumentById",
 *      tags={"Shared Document"},
 *      summary="Update shared document by ID",
 *      description="Updates a shared document by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the shared document",
 *          required=true,
 *          @OA\Schema(
 *              type="string",
 *          ),
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Updated shared document data",
 *          @OA\JsonContent(
 *              @OA\Property(property="privilege", type="string", description="Updated privilege", enum={"r", "w", "rw"}),
 *              @OA\Property(property="doctor_id", type="integer", description="Doctor ID", example=123),
 *              @OA\Property(property="document_id", type="integer", description="Document ID", example=456),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Shared document updated successfully",
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Shared document not found",
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Internal server error",
 *      )
 * )
 */    
    public function update(Request $request, string $id)
    {
        
        $validator= validator::make($request->all(),[                    
            'privilege' => 'required|in:r,w,rw', 
           // 'doctor_id' => 'required|numeric',
           // 'document_id' => 'required|numeric', 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'erreur' => $validator->messages()],422);
        }else{
            $shared_document = SharedDocument::find($id);
            if($shared_document){//on doit tester si ces deux id restent les mm
                if($shared_document->doctor_id == $request->doctor_id && $shared_document->document_id == $request->document_id ){
                    $updateResult=$shared_document->update($request->all());
                    if (!$updateResult) {
                        return response()->json(['status' => 500,
                                                'message' => __('messages.shared_document').__('messages.erreur')], 500);
                    }
                    return response()->json([
                        'status' => 200,
                        'message' => __('messages.shared_document').__('messages.updated')],200);
                }else{
                    return response()->json([
                        'status' => 500,
                        'message' => __('messages.shared_document').__('messages.erreur')],500);
                }
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => __('messages.shared_document').__('messages.not_found')],404);
            }  
        }
    }

/**
 * @OA\Delete(
 *      path="/api/sharedDocument/{id}",
 *      operationId="deleteSharedDocumentById",
 *      tags={"Shared Document"},
 *      summary="Delete shared document by ID",
 *      description="Deletes a shared document by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the shared document",
 *          required=true,
 *          @OA\Schema(
 *              type="string",
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Shared document deleted successfully",
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Shared document not found",
 *      )
 * )
 */
    public function destroy(string $id)
    {
        $shared_document = SharedDocument::find($id);
        if ($shared_document) {
            $shared_document->delete();
            return response()->json([
                'status' => 200,
                'message' => __('messages.shared_document').__('messages.deleted')],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => __('messages.shared_document').__('messages.not_found')],404); 
        }
    }   
}
