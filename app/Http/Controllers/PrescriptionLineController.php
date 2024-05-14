<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\PrescriptionLine;
use App\Models\PatientRecord;
class PrescriptionLineController extends Controller
{

/**
 * @OA\Get(
 *      path="/api/prescription_line",
 *      operationId="getPrescription_lineList",
 *      tags={"Prescription Line"},
 *      summary="Get list of prescription_line",
 *      description="Returns list of prescription_line",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation"
 *       ),
 *      @OA\Response(
 *          response=404,
 *          description="No prescription line found"
 *      )
 * )
 */
    public function index()
    {
        $prescription_line = PrescriptionLine::all();
        if ($prescription_line->count()>0) {
            return response()->json(['status' => 200,
                                      'prescription_line'=> $prescription_line ], 200);
        }else{
            return response()->json(['status' => 404,
                                     'message' => __('messages.prescription_line').__('messages.not_found')], 404);
        }
    }

/**
 * @OA\Post(
 *      path="/api/prescription_line",
 *      operationId="storePrescriptionLine",
 *      tags={"Prescription Line"},
 *      summary="Create a new prescription line",
 *      description="Creates a new prescription line with the provided details.",
 *      @OA\RequestBody(
 *          required=true,
 *          description="Prescription line object to be created",
 *          @OA\JsonContent(
 *              required={"medcine_name", "dosage", "document_id"},
 *              @OA\Property(property="medcine_name", type="string", description="Name of the medicine", example="Medicine A"),
 *              @OA\Property(property="dosage", type="string", description="Dosage of the medicine", example="2 tablets per day"),
 *              @OA\Property(property="document_id", type="integer", description="ID of the document associated with the prescription line", example=123),
 *              @OA\Property(property="times", type="integer", description="Times per day (optional)", example=3),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Prescription line created successfully",
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
    public function store(Request $request)
    {
        $validator= validator::make($request->all(),[ 
            'medcine_name'=> 'required|string',
            'dosage'=> 'required|string',
            'document_id'=> 'required|numeric|min:0',  
            'times'=>'nullable|integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'erreur' => $validator->messages()],422);
        }
        else{
            $prescription_line = PrescriptionLine::create(($request->all()));
            if($prescription_line){
                return response()->json([
                    'status' => 200,
                    'message' => __('messages.prescription_line').__('messages.created')],200);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => __('messages.erreur')],500);
            }
        }
    }

/**
 *  @OA\Get(
 *      path="/api/prescription_line/{id}",
 *      operationId="showPrescriptionLine",
 *      tags={"Prescription Line"},
 *      summary="Get a specific Prescription_line type by ID",
 *      description="Returns the Prescription_line with the specified ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the Prescription_line to retrieve",
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
 *          description="Prescription_line not found",
 *         
 *      )
 * )
 */
    public function show(string $id)
    {
        $prescription_line = PrescriptionLine::find($id);
        if ($prescription_line) {
            return response()->json([
                'status' => 200,
                'prescription_line' => $prescription_line],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => __('messages.prescription_line').__('messages.not_found')],404); 
        }  
    }

/**
 * @OA\Put(
 *      path="/api/prescription_line/{id}",
 *      operationId="updatePrescriptionLine",
 *      tags={"Prescription Line"},
 *      summary="Update a prescription line",
 *      description="Updates an existing prescription line with the provided details.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the prescription line to update",
 *          required=true,
 *          @OA\Schema(
 *              type="string",
 *          ),
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Prescription line object data to update",
 *          @OA\JsonContent(
 *              required={"medcine_name", "dosage"},
 *              @OA\Property(property="medcine_name", type="string", description="Name of the medicine", example="Medicine A"),
 *              @OA\Property(property="dosage", type="string", description="Dosage of the medicine", example="2 tablets per day"),
 *              @OA\Property(property="document_id", type="integer", description="ID of the document associated with the prescription line (optional)", example=123),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Prescription line updated successfully",
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Prescription line not found",
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
            'medcine_name'=> 'required|string',
            'dosage'=> 'required|string',
            //'document_id'=> 'required|unsignedBigInteger',  
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'erreur' => $validator->messages()],422);
        }else{
            $prescription_line = PrescriptionLine::find($id);
            if($prescription_line){
                if($prescription_line->document_id == $request->document_id){
                    $updateResult=$prescription_line->update(($request->all()));
                    if (!$updateResult) {
                        return response()->json(['status' => 500,
                                                'message' => __('messages.prescription_line').__('messages.erreur')], 500);
                    }
                    return response()->json([
                        'status' => 200,
                        'message' => __('messages.prescription_line').__('messages.updated')],200);
                }else{
                    return response()->json([
                        'status' => 500,
                        'message' => __('messages.erreur')],500);
                }
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => __('messages.prescription_line').__('messages.not_found')],404);
            }  
        }
       }

/**
 * @OA\Delete(
 *      path="/api/prescription_line/{id}",
 *      operationId="deletePrescriptionLine",
 *      tags={"Prescription Line"},
 *      summary="delete a prescription line",
 *      description="deletes an existing prescription line with the provided details.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the prescription line to delete",
 *          required=true,
 *          @OA\Schema(
 *              type="string",
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="prescription line deleted successfully",
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="prescription line not found",
 *      )
 * )
 */       
    public function destroy(string $id)
    {
        $prescription_line = PrescriptionLine::find($id);
        if ($prescription_line) {
            $prescription_line->delete();
            return response()->json([
                'status' => 200,
                'message' => __('messages.prescription_line').__('messages.deleted')],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => __('messages.prescription_line').__('messages.not_found')],404); 
    }
} 
}