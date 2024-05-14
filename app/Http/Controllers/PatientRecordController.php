<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\PatientRecord;
use App\Models\Consultation;
use OpenApi\Annotations as OA;
 
 
use Illuminate\Support\Facades\Validator;
 
class PatientRecordController extends Controller
{
   
       /**
     * @OA\Get(
     *      path="/api/patientRecords",
     *      operationId="getPatientRecordsList",
     *      tags={"Patient Records"},
     *      summary="Get list of patient records",
     *      description="Returns list of patient records",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="No patient records found"
     *      )
     * )
     */
    public function index()
    {
        $patientRecords = PatientRecord::all();
 
        if ($patientRecords->isEmpty()) {
            return response()->json(['status' => 404,
                                    'message' => __('messages.patient_record').__('messages.not_found')], 404);
        }
 
        return response()->json(['status' => 200, 'patient_records' => $patientRecords], 200);
    }
 
 
     /**
      * Crée un nouveau dossier patient.
     * @OA\Post(
     *      path="/api/patientRecords",
     *      operationId="storePatientRecord",
     *      tags={"Patient Records"},
     *      summary="Create a new patient record",
     *      description="Creates a new patient record",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Patient record data",
     *          @OA\JsonContent(
     *              required={"numero", "description", "created_by", "patient_id", "doctor_id"},
     *              @OA\Property(property="numero", type="integer"),
     *              @OA\Property(property="description", type="string"),
     *              @OA\Property(property="created_by", type="integer"),
     *              @OA\Property(property="patient_id", type="integer"),
     *              @OA\Property(property="doctor_id", type="integer"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Patient record created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=201),
     *              @OA\Property(property="message", type="string", example="Patient record created successfully")
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
     *              @OA\Property(property="message", type="string", example="An error occurred while creating the patient record")
     *          )
     *      )
     * )
     */
 
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero' => 'required|integer',
            'description' => 'string',
            'patient_id' => 'required|numeric|min:0',
            'doctor_id' => 'required|numeric|min:0',
 
        ]);
 
 
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'erreur' => $validator->messages()], 422);
        }
 
     
        $exist=PatientRecord::where('doctor_id',$request->doctor_id)->where('patient_id',$request->patient_id)->count()!=0;
        if ($exist==0)
        {
            $patientRecord = PatientRecord::create(($request->all()));
            if ($patientRecord) {
            return response()->json(['status' => 201,
                                    'message' => __('messages.patient_record').__('messages.created')], 201);
            }else {
            return response()->json(['status' => 500,
                                    'message' => __('messages.patient_record').__('messages.erreur')], 500);
        }
        }else{
            return response()->json(['status' => 500,
                                    'message' => __('messages.patient_record').__('messages.exist')], 500);
        }
    }
 
     /**
    * Affiche un dossier patient spécifique.
 
 * @OA\Get(
 *      path="/api/patientRecords/{id}",
 *      operationId="getPatientRecordById",
 *      tags={"Patient Records"},
 *      summary="Get a specific patient record",
 *      description="Returns details of a specific patient record",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the patient record to retrieve",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=200),
 *              @OA\Property(property="message", type="string", example="Patient record found successfully"),
 *              @OA\Property(
 *                  property="patient_record",
 *                  type="object",
 *                  @OA\Property(property="id", type="integer", format="int64"),
 *                  @OA\Property(property="numero", type="integer"),
 *                  @OA\Property(property="description", type="string"),
 *                  @OA\Property(property="created_by", type="integer"),
 *                  @OA\Property(property="patient_id", type="integer"),
 *                  @OA\Property(property="doctor_id", type="integer"),
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Patient record not found",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=404),
 *              @OA\Property(property="message", type="string", example="Patient record not found")
 *          )
 *      )
 * )
 */
 
    public function show(string $id)
    {
        $patientRecord = PatientRecord::find($id);
 
        if (!$patientRecord) {
            return response()->json(['status' => 404,
                                    'message' => __('messages.patient_record').__('messages.not_found')], 404);
        }
 
        return response()->json(['status' => 200, 'patient_record' => $patientRecord], 200);
    }
 
    /**
    * Met à jour un dossier patient existant.
 
 * @OA\Put(
 *      path="/api/patientRecords/{id}",
 *      operationId="updatePatientRecord",
 *      tags={"Patient Records"},
 *      summary="Update a patient record",
 *      description="Updates an existing patient record",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the patient record to update",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Patient record data",
 *          @OA\JsonContent(
 *              required={"numero", "description", "patient_id", "doctor_id"},
 *              @OA\Property(property="numero", type="integer"),
 *              @OA\Property(property="description", type="string"),
 *              @OA\Property(property="patient_id", type="integer"),
 *              @OA\Property(property="doctor_id", type="integer"),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Patient record updated successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=200),
 *              @OA\Property(property="message", type="string", example="Patient record updated successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Patient record not found",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=404),
 *              @OA\Property(property="message", type="string", example="Patient record not found")
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
 *              @OA\Property(property="message", type="string", example="An error occurred while updating the patient record")
 *          )
 *      )
 * )
 */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'numero' => 'required|integer', // //ou bien numeric + auto increment ou non
            'description' => 'string',
            //cle primaire de Patient record=patient id+doctor id : donc les 2 attributs ena7iwhom juste fel update()
        //recherche dans lautre micro service du patient et doctor :mech tawa
        // bech n7el dossier jdid lazem nthabet nafs etbib ma5dmch m3a nafs l patient deja dossier
 
        ]);
 
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'erreur' => $validator->messages()], 422);
        }
        $patientRecord = PatientRecord::find($id);
        if (!$patientRecord) {
            return response()->json(['status' => 404,
                                    'message' => __('messages.patient_record').__('messages.not_found')], 404);
        }
        if  ($request->doctor_id && $patientRecord->doctor_id != $request->doctor_id ||$request->patient_id && $patientRecord->patient_id != $request->patient_id) {
            return response()->json(['status' => 404,
                                    'message' => __('messages.patient_record').__('messages.erreur')], 404);
        }
        $updateResult = $patientRecord->update($request->all());
        if (!$updateResult) {
                return response()->json(['status' => 500,
                                        'message' => __('messages.patient_record').__('messages.erreur')], 500);
            }
        return response()->json(['status' => 200,
                                'message' => __('messages.patient_record').__('messages.updated')], 200);
                               
    }
 
    /**
     * Supprime un dossier patient.
   *delete consultation+documents en relation avec ce record $consul :3malneha bel delete('en cascade') fel les fichiers de migrations mte3 documents
   *w fel 2eme fichier de migration
     */
 
     /**
 * @OA\Delete(
 *      path="/api/patientRecords/{id}",
 *      operationId="deletePatientRecord",
 *      tags={"Patient Records"},
 *      summary="Delete a patient record",
 *      description="Deletes a specific patient record",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the patient record to delete",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Response(
 *          response=204,
 *          description="Patient record deleted successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=204),
 *              @OA\Property(property="message", type="string", example="Patient record deleted successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Patient record not found",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=404),
 *              @OA\Property(property="message", type="string", example="Patient record not found")
 *          )
 *      )
 * )
 */
    public function destroy(string $id)
    {
        $patientRecord = PatientRecord::find($id);
 
        if (!$patientRecord) {
            return response()->json(['status' => 404,
                                    'message' => __('messages.patient_record').__('messages.not_found')], 404);
        }
 
        $patientRecord->delete();
 
        return response()->json(['status' => 204,
                                'message' => __('messages.patient_record').__('messages.deleted')], 204);
    }
 
 /**
 * Affiche toutes les consultations associées à un dossier patient spécifique.
 *
 * @OA\Get(
 *      path="/api/patientRecords/{id}/consultations",
 *      operationId="getConsultations",
 *      tags={"Get Consultation By Patient Record"},
 *      summary="Get all consultations associated with a specific patient record",
 *      description="Returns all consultations associated with a specific patient record",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the patient record",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation. Returns the consultations associated with the specified patient record.",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=200),
 *              @OA\Property(property="consultations", type="array", @OA\Items(
 *                  @OA\Property(property="id", type="integer"),
 *                  @OA\Property(property="date", type="string", format="date"),
 *                  @OA\Property(property="doctor_id", type="integer"),
 *                  @OA\Property(property="patient_id", type="integer"),
 *                  @OA\Property(property="description", type="string")
 *              ))
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Patient record not found. Indicates that no patient record matching the specified ID was found.",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=404),
 *              @OA\Property(property="message", type="string", example="Patient record not found")
 *          )
 *      )
 * )
 */
        public function getConsultations(string $id)
{
    $patientRecord = PatientRecord::find($id);
 
    if (!$patientRecord) {
        return response()->json(['status' => 404, 'message' => __('messages.patient_record').__('messages.not_found')], 404);
    }
 
    $consultations = $patientRecord->consultations;
 
    return response()->json(['status' => 200, 'consultations' => $consultations], 200);
}
 
 
}