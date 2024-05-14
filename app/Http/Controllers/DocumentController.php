<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\PatientRecord;
use App\Models\DocumentType;
use Illuminate\Support\Facades\Validator;
class DocumentController extends Controller
{

/**
 * @OA\Get(
 *      path="/api/documents",
 *      operationId="getDocumentsList",
 *      tags={"Documents"},
 *      summary="Get list of documents",
 *      description="Returns list of documents",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation"
 *       ),
 *      @OA\Response(
 *          response=404,
 *          description="No documents found"
 *      )
 * )
 */
    public function index()
    {
        $documents = Document::all();
        if ($documents->count()>0) {
            return response()->json(['status' => 200,
                                      'document'=> $documents ], 200);
        }else{
            return response()->json(['status' => 404,
            'message' => __('messages.document').__('messages.not_found')], 404);
        }
    }


    /**
 * @OA\Post(
 *      path="/api/documents",
 *      operationId="storeDocument",
 *      tags={"Documents"},
 *      summary="Create a new document",
 *      description="Create a new document with the provided data",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"label", "path"},
 *              @OA\Property(property="label", type="string", example="Document Label"),
 *              @OA\Property(property="path", type="string", example="path/to/document.pdf"),
 *              @OA\Property(property="document_type_id", type="integer", example=1),
 *              @OA\Property(property="patient_record_id", type="integer", example=1),
 *              @OA\Property(property="consultation_id", type="integer", example=1),
 *              @OA\Property(property="created_by", type="integer", example=1),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Document created successfully"
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
            'label' => 'required|string|max:255',     
            'path' => 'required|string', 
            'document_type_id'=> 'required_without:patient_record_id|numeric|min:0',
            'patient_record_id'=> 'required_without:document_type_id|numeric|min:0',//on cherche une seule va etre required
            'consultation_id'=> 'numeric|min:0',
            'created_by'=> 'numeric|min:0', 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'erreur' => $validator->messages()],422);
        }
        else{
            $document = Document::create($request->all());
            if($document){
                return response()->json([
                    'status' => 200,
                    'message' => __('messages.document').__('messages.created')],200);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => __('messages.erreur')],500);
            }
        }
    }

/**
 * @OA\Get(
 *      path="/api/documents/{id}",
 *      operationId="showDocumentById",
 *      tags={"Documents"},
 *      summary="Get a specific document by ID",
 *      description="Returns the document with the specified ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the document to retrieve",
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
 *          description="Document not found",
 *         
 *      )
 * )
 */
    public function show(string $id)
    {
        $document = Document::find($id);
        if ($document) {
            return response()->json([
                'status' => 200,
                'document' => $document],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => __('messages.document').__('messages.not_found')],404); 
        }  
    }

/**
 * @OA\Put(
 *      path="/api/documents/{id}",
 *      operationId="updateDocument",
 *      tags={"Documents"},
 *      summary="Update an existing document",
 *      description="Update the specified document",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the document to update",
 *          required=true,
 *          @OA\Schema(
 *              type="string",
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Updated document object",
 *          @OA\JsonContent(
 *              @OA\Property(property="label", type="string", example="Updated Label"),
 *              @OA\Property(property="path", type="string", example="/updated/path/to/document.pdf"),
 *              @OA\Property(property="document_type_id", type="numeric", example="2"),
 *              @OA\Property(property="patient_record_id", type="numeric", example="3"),
 *              @OA\Property(property="consultation_id", type="numeric", example="3"),
 *              @OA\Property(property="created_by", type="numeric", example="3"),
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Document not found",
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
        $document = Document::find($id);
        if(!$document){
            return response()->json([
                'status' => 404,
                'message' => __('messages.document').__('messages.not_found')],404);
        }  
        $validator= validator::make($request->all(),[                         //verifier la validité des données:Request qui contient les données envoyées par le formulaire de création 
            'label' => 'required|string|max:255',    //champ oblig de type string 
            'path' => 'required|string',     //champ oblig de type string 
            //'document_type_id'=> 'required|numeric|min:0',
            //'patient_record_id'=> 'required|numeric|min:0',
            //'consultation_id'=> 'required|numeric|min:0',
            //'created_by'=> 'required|numeric|min:0', 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'erreur' => $validator->messages()],422);
        }else{
           if($document->document_id == $request->document_id &&
            $document->patient_record_id == $request->patient_record_id &&
            $document->consultation_id == $request->consultation_id &&
            $document->created_by == $request->created_by){
                $updateResult=$document->update([
                    'label' => $request->label,
                    'path' =>$request->path,
                    //'document_type_id'=>$request->document_type_id,
                    //'patient_record_id'=>$request->patient_record_id,
                    //'consultation_id'=>$request->consultation_id,
                    //'created_by'=>$request->created_by, 
                ]);
                if (!$updateResult) {
                    return response()->json(['status' => 500,
                                            'message' => __('messages.document').__('messages.erreur')], 500);
                }
                return response()->json([
                    'status' => 200,
                    'message' => __('messages.document').__('messages.updated')],200);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => __('messages.erreur')],500);
            }
        }
       }

/**
 * @OA\Delete(
 *      path="/api/documents/{id}",
 *      operationId="deleteDocument",
 *      tags={"Documents"},
 *      summary="Delete a document",
 *      description="Deletes a document by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the document to delete",
 *          required=true,
 *          @OA\Schema(
 *              type="string",
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Document deleted successfully",
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Document not found",
 *      )
 * )
 */
    public function destroy(string $id)
    {
        $document = Document::find($id);
        if ($document) {
            $document->delete();
            return response()->json([
                'status' => 200,
                'message' => __('messages.document').__('messages.deleted')],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => __('messages.document').__('messages.not_found')],404); 
        }   
    }  

/**
 * @OA\Get(
 *      path="/api/prescriptions_by_patient/{patientId}",
 *      operationId="getPrescriptionsByPatientId",
 *      tags={"Prescriptions By Patient"},
 *      summary="Get prescriptions by patient ID",
 *      description="Retrieves prescriptions associated with a patient by their ID",
 *      @OA\Parameter(
 *          name="patientId",
 *          in="path",
 *          description="ID of the patient",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Prescriptions found",
 *                  ),
 *      @OA\Response(
 *          response=404,
 *          description="Prescriptions not found",
 *      ),
 * )
 */
    public function getPrescriptionsByPatientId($patientId) {
        $patientRecords = PatientRecord::where('patient_id', $patientId)->get();;
        //on a recuperé le premier enregistrement de patient record du patient id demandé
        if ($patientRecords->isEmpty()) {
            // Gérer le cas où l'enregistrement du patient n'est pas trouvé(ya3ni 0 patient ou oatientrecord)
            return response()->json([
                'status' => 404,
                'message' => __('messages.patient_record').__('messages.not_found')
            ], 404);
        }
        // Récupérer les types de documents de prescription associés à l'enregistrement du patient
        $prescriptionDocumentTypes = [];
        foreach ($patientRecords as $patientRecord) {
            $prescriptions = DocumentType::whereHas('documents', function ($query) use ($patientRecord) {
                $query->where('patient_record_id', $patientRecord->id);
            })->where('label', 'Prescription')->get();

            if (!$prescriptions->isEmpty()) {
                $prescriptionDocumentTypes[$patientRecord->id] = $prescriptions;
            }
        }
        //isempty on lutilise pour une collection et non un tableau
        if (empty($prescriptionDocumentTypes)) {
            return response()->json([
                'status' => 404,
                'message' => __('messages.prescription').__('messages.not_found')
            ], 404);
        }
        return response()->json([
            'status' => 200,
            'prescriptions' => $prescriptionDocumentTypes
        ], 200);
    }

/**
 * @OA\Get(
 *      path="/api/prescriptions_by_patientrecord/{patientRecordId}",
 *      operationId="getPrescriptionsByPatientRecord",
 *      tags={"Prescriptions By Patient Record"},
 *      summary="Get prescriptions by patient record",
 *      description="Retrieves prescriptions associated with a patient record ",
 *       @OA\Parameter(
 *          name="patientRecordId",
 *          in="path",
 *          description="ID of the patient record",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Prescriptions found",
 *                  ),
 *      @OA\Response(
 *          response=404,
 *          description="Prescriptions not found",
 *      ),
 * )
 */    
    public function getPrescriptionsByPatientRecordId($patientRecordId)
    {
        // Récupérer les types de documents de prescription associés à l'enregistrement du patient spécifié
        $prescriptionDocumentTypes = DocumentType::whereHas('documents', function ($query) use ($patientRecordId) {
                $query->where('patient_record_id', $patientRecordId);
            })
            ->where('label', 'Prescription')
            ->get();
        
            if ($prescriptionDocumentTypes->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => __('messages.prescription').__('messages.not_found')
                ], 404);
            }
        
            return response()->json([
                'status' => 200,
                'prescriptions' => $prescriptionDocumentTypes
            ], 200);
    }

}

