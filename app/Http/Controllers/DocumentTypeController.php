<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\DocumentType;
class DocumentTypeController extends Controller
{
    
/**
 * @OA\Get(
 *      path="/api/document_types",
 *      operationId="getDocumenttypesList",
 *      tags={"Document Types"},
 *      summary="Get list of document types",
 *      description="Returns list of document types",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation"
 *       ),
 *      @OA\Response(
 *          response=404,
 *          description="No document types found"
 *      )
 * )
 */    
    public function index()
    {
        $document_types = DocumentType::all();
        if ($document_types->count()>0) {
            return response()->json(['status' => 200,
                                      'document type'=> $document_types ], 200);
        }else{
            return response()->json(['status' => 404,
            'message' => __('messages.document').__('messages.not_found')], 404);
        }
    }


/**
 * @OA\Post(
 *      path="/api/document_types",
 *      operationId="storeDocumentType",
 *      tags={"Document Types"},
 *      summary="Store a new document type",
 *      description="Creates a new document type with the provided label.",
 *      @OA\RequestBody(
 *          required=true,
 *          description="Document type object to be stored",
 *          @OA\JsonContent(
 *              required={"label"},
 *              @OA\Property(property="label", type="string", description="Label of the document type", example="Certificate", enum={"Certificate", "Prescription", "IRM", "Radio", "Analysis", "Note"}),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Document type created successfully"
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error"
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Internal server error"
 *      )
 * )
 */    
    public function store(Request $request)
    {
        $validator= validator::make($request->all(),[ 
            'label' => 'required|in:Certificate,Prescription,IRM,Radio,Analysis,Note',    
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'erreur' => $validator->messages()],422);
        }
        else{
            $document_types = DocumentType::create($request->all());
            if($document_types){
                return response()->json([
                    'status' => 200,
                    'message' => __('messages.document_type').__('messages.created')],200);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => __('messages.erreur')],500);
            }
        }
    }

/**
 *  @OA\Get(
 *      path="/api/document_types/{id}",
 *      operationId="showDocumentType",
 *      tags={"Document Types"},
 *      summary="Get a specific document type by ID",
 *      description="Returns the document type with the specified ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the document type to retrieve",
 *          required=true,
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *         
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Document type not found",
 *         
 *      )
 * )
 */
    public function show(string $id)
    {
        $document_types = DocumentType::find($id);
        if ($document_types) {
            return response()->json([
                'status' => 200,
                'document' => $document_types],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => __('messages.document_type').__('messages.not_found')],404); 
        }  
    }

/**
 * @OA\Put(
 *      path="/api/document_types/{id}",
 *      operationId="updateDocumentType",
 *      tags={"Document Types"},
 *      summary="Update an existing document type",
 *      description="Updates an existing document type with the provided label.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the document type to update",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          ),
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Document type object to be updated",
 *          @OA\JsonContent(
 *              required={"label"},
 *              @OA\Property(property="label", type="string", description="Label of the document type", example="Certificate", enum={"Certificate", "Prescription", "IRM", "Radio", "Analysis", "Note"}),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Document type updated successfully",
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Document type not found",
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Internal server error",
 *      ),
 * )
 */
    public function update(Request $request, string $id)
    { 
        $validator= validator::make($request->all(),[                      
            'label' => 'required|in:Certificate,Prescription,IRM,Radio,Analysis,Note',       
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'erreur' => $validator->messages()],422);
        }else{
            $document_types = DocumentType::find($id);
            if($document_types){
                $updateResult=$document_types->update($request->all());
                if (!$updateResult) {
                    return response()->json(['status' => 500,
                                            'message' => __('messages.document_type').__('messages.erreur')], 500);
                }
                return response()->json([
                    'status' => 200,
                    'message' => __('messages.document_type').__('messages.updated')],200);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => __('messages.document_type').__('messages.not_found')],404);
            }  
        }
       }


/**
 * @OA\Delete(
 *      path="/api/document_types/{id}",
 *      operationId="deleteDocumentType",
 *      tags={"Document Types"},
 *      summary="Delete a document type",
 *      description="Deletes a document type by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the document type to delete",
 *          required=true,
 *          @OA\Schema(
 *              type="string",
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Document type deleted successfully",
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Document type not found",
 *      )
 * )
 */
    public function destroy(string $id)
    {
        $document_types = DocumentType::find($id);
        if ($document_types) {
            $document_types->delete();
            return response()->json([
                'status' => 200,
                'message' => __('messages.document_type').__('messages.deleted')],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => __('messages.document_type').__('messages.not_found')],404); 
    }
} 
}
