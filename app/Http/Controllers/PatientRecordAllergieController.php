<?php
 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\PatientRecordAllergie;
use App\Models\Allergie;
use Illuminate\Support\Facades\Validator;
 
class PatientRecordAllergieController extends Controller
{
   /**
     * Display a listing of the resource.
     * @OA\Get(
     *      path="/api/patientRecordAllergie",
     *      operationId="getPatientRecordAllergiesList",
     *      tags={"Patient Record Allergies"},
     *      summary="Get list of patient record allergies",
     *      description="Returns list of patient record allergies",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200),
     *              @OA\Property(
     *                  property="patient_record_allergies",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="allergie_id", type="integer"),
     *                      @OA\Property(property="patient_record_id", type="integer"),
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No patient record allergies found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=404),
     *              @OA\Property(property="message", type="string", example="No patient record allergies found")
     *          )
     *      )
     * )
     */
    public function index()
    {
        $patientRecordAllergie = PatientRecordAllergie::all();
 
        if ($patientRecordAllergie->isEmpty()) {
            return response()->json(['status' => 404,
                                     'message' => __('messages.patient_record_allergy').__('messages.not_found')], 404);
        }
 
        return response()->json(['status' => 200,
                                 'patient_record_allergy' => $patientRecordAllergie], 200);
    }
 
 
    /**
     * Store a newly created resource in storage.
     * @OA\Post(
     *      path="/api/patientRecordAllergie",
     *      operationId="storePatientRecordAllergie",
     *      tags={"Patient Record Allergies"},
     *      summary="Create a new patient record allergy",
     *      description="Creates a new patient record allergy",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Patient record allergy data",
     *          @OA\JsonContent(
     *              required={"allergie_id", "patient_record_id"},
     *              @OA\Property(property="allergie_id", type="integer"),
     *              @OA\Property(property="patient_record_id", type="integer"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Patient record allergy created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=201),
     *              @OA\Property(property="message", type="string", example="Patient record allergy created successfully")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=422),
     *              @OA\Property(property="errors", type="object", example={"field_name": {"Error message"}})
     *          )
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="Patient record allergy already exists",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=409),
     *              @OA\Property(property="message", type="string", example="Patient record allergy already exists")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=500),
     *              @OA\Property(property="message", type="string", example="An error occurred while creating the patient record allergy")
     *          )
     *      )
     * )
     */
 
    public function store(Request $request)
    {
        $validator= validator::make($request->all(),[
            'allergie_id' => 'required|numeric|min:0',
            'patient_record_id' => 'required|numeric|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'erreur' => $validator->messages()],422);
        }
        else{
            $existing_patient_record_allergie = PatientRecordAllergie::all();  // Récupération de tous les enregistrements existants
            foreach ($existing_patient_record_allergie as $existingRecord) {
                if ($existingRecord->allergie_id == $request->allergie_id && $existingRecord->patient_record_id == $request->patient_record_id) {
                    return response()->json([
                        'status' => 409,
                        'message' => __('messages.patient_record_allergy') . __('messages.exist')
                    ], 409);
                }
            }
           
            $patient_record_allergie = PatientRecordAllergie::create($request->all());
            if($patient_record_allergie){
                return response()->json([
                    'status' => 200,
                    'message' => __('messages.patient_record_allergy').__('messages.created')],200);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => __('messages.patient_record_allergy').__('messages.erreur')],500);
            }
        }
    }
 
     /**
     * Display the specified resource.
     * @OA\Get(
     *      path="/api/patientRecordAllergie/{id}",
     *      operationId="getPatientRecordAllergieById",
     *      tags={"Patient Record Allergies"},
     *      summary="Get a specific patient record allergy",
     *      description="Returns details of a specific patient record allergy",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the patient record allergy to retrieve",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200),
     *              @OA\Property(property="patient_record_allergie", type="object",
     *                  @OA\Property(property="id", type="integer"),
     *                  @OA\Property(property="allergie_id", type="integer"),
     *                  @OA\Property(property="patient_record_id", type="integer"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Patient record allergy not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=404),
     *              @OA\Property(property="message", type="string", example="Patient record allergy not found")
     *          )
     *      )
     * )
     */
 
    public function show(string $id)
    {
        $patient_record_allergie = PatientRecordAllergie::find($id);
        if ($patient_record_allergie) {
            return response()->json([
                'status' => 200,
                'patient_record_allergie' => $patient_record_allergie],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => __('messages.patient_record_allergy').__('messages.not_found')],404);
        }
    }
 
 
    /**
     * Update the specified resource in storage.
     * @OA\Put(
     *      path="/api/patientRecordAllergie/{id}",
     *      operationId="updatePatientRecordAllergie",
     *      tags={"Patient Record Allergies"},
     *      summary="Update an existing patient record allergy",
     *      description="Updates an existing patient record allergy",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the patient record allergy to update",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Patient record allergy data",
     *          @OA\JsonContent(
     *              @OA\Property(property="allergie_id", type="integer"),
     *              @OA\Property(property="patient_record_id", type="integer"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Patient record allergy updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200),
     *              @OA\Property(property="message", type="string", example="Patient record allergy updated successfully")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Patient record allergy not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=404),
     *              @OA\Property(property="message", type="string", example="Patient record allergy not found")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=422),
     *              @OA\Property(property="errors", type="object", example={"field_name": {"Error message"}})
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=500),
     *              @OA\Property(property="message", type="string", example="An error occurred while updating the patient record allergy")
     *          )
     *      )
     * )
     */
 
     public function update(Request $request, string $id)
{
    $validator = Validator::make($request->all(), [
        'allergie_id' => 'required|numeric|min:0',
        'patient_record_id' => 'required|numeric|min:0',
    ]);
 
    if ($validator->fails()) {
        return response()->json([
            'status' => 422,
            'errors' => $validator->messages()
        ], 422);
    }
 
    $patient_record_allergie = PatientRecordAllergie::find($id);
 
    if (!$patient_record_allergie) {
        return response()->json([
            'status' => 404,
            'message' => __('messages.patient_record_allergy') . __('messages.erreur')
        ], 404);
    }
 
    // Vérifier si les IDs sont identiques
    if ($patient_record_allergie->allergie_id != $request->allergie_id || $patient_record_allergie->patient_record_id != $request->patient_record_id) {
        return response()->json([
            'status' => 422,
            'message' => __('messages.patient_record_allergy') . __('messages.erreur')
        ], 422);
    }
 
    $updateResult = $patient_record_allergie->update($request->all());
 
    if ($updateResult) {
        return response()->json([
            'status' => 200,
            'message' => __('messages.patient_record_allergy') . __('messages.updated')
        ], 200);
    }
 
    return response()->json([
        'status' => 500,
        'message' => __('messages.patient_record_allergy') . __('messages.erreur')
    ], 500);
}
 /**
     * Remove the specified resource from storage.
     * @OA\Delete(
     *      path="/api/patientRecordAllergie/{id}",
     *      operationId="deletePatientRecordAllergie",
     *      tags={"Patient Record Allergies"},
     *      summary="Delete a patient record allergy",
     *      description="Deletes a patient record allergy",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the patient record allergy to delete",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Patient record allergy deleted successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=204),
     *              @OA\Property(property="message", type="string", example="Patient record allergy deleted successfully")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Patient record allergy not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=404),
     *              @OA\Property(property="message", type="string", example="Patient record allergy not found")
     *          )
     *      )
     * )
     */
   
    public function destroy(string $id)
    {
        $patient_record_allergie = PatientRecordAllergie::find($id);
        if ($patient_record_allergie) {
            $patient_record_allergie->delete();
            return response()->json([
                'status' => 200,
                'message' => __('messages.patient_record_allergy').__('messages.deleted')],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => __('messages.patient_record_allergy').__('messages.not_found')],404);
        }
    }
}