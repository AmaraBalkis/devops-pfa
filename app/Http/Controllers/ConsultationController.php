<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Consultation;
use Illuminate\Support\Facades\Validator;
use App\Models\Document;
 
 
 
class ConsultationController extends Controller
{
   /**
     * @OA\Get(
     *      path="/api/consultations",
     *      operationId="getConsultationsList",
     *      tags={"Consultations"},
     *      summary="Get list of consultations",
     *      description="Returns list of consultations",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="No consultations found"
     *      )
     * )
     */
 
    public function index()
    {
        $consultations = Consultation::all();
       
        if ($consultations->isEmpty()) {
            return response()->json(['message' => __('messages.consultation').__('messages.not_found')], 404);
        } else {
            return response()->json(['status' => 200, 'consultations' => $consultations], 200);
        }
    }
 
    /**
     * Crée une nouvelle consultation.
     */
 
    /**
 * @OA\Post(
 *      path="/api/consultations",
 *      operationId="storeConsultation",
 *      tags={"Consultations"},
 *      summary="Create a new consultation",
 *      description="Creates a new consultation",
 *      @OA\RequestBody(
 *          required=true,
 *          description="Consultation data",
 *          @OA\JsonContent(
 *              required={"illness", "weight", "height", "pressure", "observations", "diagnostic", "description", "visit_price", "payment_status", "payment_date", "started_at", "ended_at"},
 *              @OA\Property(property="illness", type="string"),
 *              @OA\Property(property="weight", type="number", format="float"),
 *              @OA\Property(property="height", type="number", format="float"),
 *              @OA\Property(property="pressure", type="number", format="float"),
 *              @OA\Property(property="observations", type="string"),
 *              @OA\Property(property="diagnostic", type="string"),
 *              @OA\Property(property="description", type="string"),
 *              @OA\Property(property="visit_price", type="number", format="float"),
 *              @OA\Property(property="payment_status", type="boolean"),
 *              @OA\Property(property="payment_date", type="string", format="date"),
 *              @OA\Property(property="started_at", type="string", format="time"),
 *              @OA\Property(property="ended_at", type="string", format="time"),
 *              @OA\Property(property="consultation_type_id", type="integer", example=1),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="Consultation created successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=201),
 *              @OA\Property(property="message", type="string", example="Consultation created successfully")
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
 *              @OA\Property(property="message", type="string", example="An error occurred while creating the consultation")
 *          )
 *      )
 * )
 */
 
 
    public function store(Request $request)
    {
        // Validation des données
        $validator = validator::make($request->all(), [
            'illness' => 'string',
            'weight' => 'numeric',
            'height' => 'numeric',
            'pressure' => 'numeric',
            'observations' => 'string',
            'diagnostic' => 'string',
            'description' => 'string',
            'visit_price' => 'numeric',
            'payment_status' => 'boolean',
            'payment_date' => 'date',
            'started_at' => 'date_format:H:i:s',
            'ended_at' => 'date_format:H:i:s',
            'consultation_type_id'=> 'required|numeric|min:0',
        ]);
 
        // Vérification des erreurs de validation
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->messages()], 422);
        }
 
        // Création de la consultation
        $consultation = Consultation::create($request->all());
 
        if ($consultation) {
            return response()->json(['status' => 201,
                                     'message' => __('messages.consultation').__('messages.created')], 201);
        } else {
            return response()->json(['status' => 500,
            'message' => __('messages.consultation').__('messages.erreur')], 500);
        }
    }
 
 /**
 * Affiche une consultation spécifique.
 *
 * @OA\Get(
 *      path="/api/consultations/{id}",
 *      operationId="getConsultationById",
 *      tags={"Consultations"},
 *      summary="Get a specific consultation",
 *      description="Returns details of a specific consultation",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the consultation to retrieve",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=200),
 *              @OA\Property(property="message", type="string", example="Consultation found successfully"),
 *              @OA\Property(
 *                  property="consultation",
 *                  type="object",
 *                  @OA\Property(property="id", type="integer", format="int64"),
 *                  @OA\Property(property="illness", type="string"),
 *                  @OA\Property(property="weight", type="number", format="float"),
 *                  @OA\Property(property="height", type="number", format="float"),
 *                  @OA\Property(property="pressure", type="number", format="float"),
 *                  @OA\Property(property="observations", type="string"),
 *                  @OA\Property(property="diagnostic", type="string"),
 *                  @OA\Property(property="description", type="string"),
 *                  @OA\Property(property="visit_price", type="number", format="float"),
 *                  @OA\Property(property="payment_status", type="boolean"),
 *                  @OA\Property(property="payment_date", type="string", format="date"),
 *                  @OA\Property(property="started_at", type="string", format="time"),
 *                  @OA\Property(property="ended_at", type="string", format="time"),
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Consultation not found",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=404),
 *              @OA\Property(property="message", type="string", example="Consultation not found")
 *          )
 *      )
 * )
 */
public function show(string $id)
{
    $consultation = Consultation::find($id);
 
    if ($consultation) {
        return response()->json(['status' => 200, 'message' => __('messages.consultation').__('messages.found'), 'consultation' => $consultation], 200);
    } else
     {
        return response()->json(['status' => 404,
                                'message' => __('messages.consultation').__('messages.not_found')], 404);
    }
}
 
/**
 * Mise à jour d'une consultation.
 *
 * @OA\Put(
 *      path="/api/consultations/{id}",
 *      operationId="updateConsultation",
 *      tags={"Consultations"},
 *      summary="Update a consultation",
 *      description="Updates an existing consultation",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the consultation to update",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Consultation data",
 *          @OA\JsonContent(
 *              required={"illness", "weight", "height", "pressure", "observations", "diagnostic", "description", "visit_price", "payment_status", "payment_date", "started_at", "ended_at"},
 *              @OA\Property(property="illness", type="string"),
 *              @OA\Property(property="weight", type="number", format="float"),
 *              @OA\Property(property="height", type="number", format="float"),
 *              @OA\Property(property="pressure", type="number", format="float"),
 *              @OA\Property(property="observations", type="string"),
 *              @OA\Property(property="diagnostic", type="string"),
 *              @OA\Property(property="description", type="string"),
 *              @OA\Property(property="visit_price", type="number", format="float"),
 *              @OA\Property(property="payment_status", type="boolean"),
 *              @OA\Property(property="payment_date", type="string", format="date"),
 *              @OA\Property(property="started_at", type="string", format="time"),
 *              @OA\Property(property="ended_at", type="string", format="time"),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Consultation updated successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=200),
 *              @OA\Property(property="message", type="string", example="Consultation updated successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Consultation not found",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=404),
 *              @OA\Property(property="message", type="string", example="Consultation not found")
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
 *              @OA\Property(property="message", type="string", example="An error occurred while updating the consultation")
 *          )
 *      )
 * )
 */
 
    public function update(Request $request, string $id)
    {
        // Validation des données
        $validator = validator::make($request->all(), [
            'illness' => 'string',
            'weight' => 'numeric',
            'height' => 'numeric',
            'pressure' => 'numeric',
            'observations' => 'string',
            'diagnostic' => 'string',
            'description' => 'string',
            'visit_price' => 'numeric',
            'payment_status' => 'boolean',
            'payment_date' => 'date',
            'started_at' => 'date_format:H:i:s',
            'ended_at' => 'date_format:H:i:s',
        ]);
 
        // Vérification des erreurs de validation
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->messages()], 422);
        }
 
        // Recherche de la consultation
        $consultation = Consultation::find($id);
 
        if (!$consultation) {
            return response()->json(['status' => 404,
                                    'message' => __('messages.consultation').__('messages.not_found')], 404);
        }
 
        // Mise à jour de la consultation
        $updateResult = $consultation->update($request->all());
 
        if (!$updateResult) {
            return response()->json(['status' => 500,
                                    'message' => __('messages.consultation').__('messages.erreur')], 500);
        }
 
        return response()->json(['status' => 200,
                                'message' => __('messages.consultation').__('messages.updated')], 200);
    }
 
    /**
 * Supprime une consultation.
 *
 * @OA\Delete(
 *      path="/api/consultations/{id}",
 *      operationId="deleteConsultation",
 *      tags={"Consultations"},
 *      summary="Delete a consultation",
 *      description="Deletes a specific consultation",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the consultation to delete",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Consultation deleted successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=200),
 *              @OA\Property(property="message", type="string", example="Consultation deleted successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Consultation not found",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=404),
 *              @OA\Property(property="message", type="string", example="Consultation not found")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Internal Server Error",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=500),
 *              @OA\Property(property="message", type="string", example="An error occurred while deleting the consultation")
 *          )
 *      )
 * )
 */
 
    public function destroy(string $id)
    {
        // Recherche de la consultation
        $consultation = Consultation::find($id);
   
        if (!$consultation) {
            return response()->json(['status' => 404,
                                    'message' => __('messages.consultation').__('messages.not_found')], 404);
        }
   
        // Récupérer les documents associés à cette consultation et qui ont patient_record_id = null
        $documentsToUpdate = Document::where('consultation_id', $consultation->id)
        ->whereNull('patient_record_id')
        ->get();
 
        // Mettre à jour les documents avec le nouvel ID de patient_record
        foreach ($documentsToUpdate as $document) {
            $document->patient_record_id = $consultation->patient_record_id;
            $document->save(); // Utilisation de save pour sauvegarder les modifications
        }
 
        // Suppression de la consultation
        $deleteResult = $consultation->delete();
   //nrecuperiw les documents tab3i l consultation welli patient record id =null, w nmchiw nbadlouh bl patientrecordid mta3 lconsultation
   //nparcouriw wnmodifiw mch bel update mais bl save
        if ($deleteResult) {
            return response()->json(['status' => 200,
                                    'message' => __('messages.consultation').__('messages.deleted')], 200);
        } else {
            return response()->json(['status' => 500,
                                    'message' => __('messages.consultation').__('messages.erreur')], 500);
        }
    }
}