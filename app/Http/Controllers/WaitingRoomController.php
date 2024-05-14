<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\WaitingRoom;
use Illuminate\Support\Facades\Validator;
 
 
class WaitingRoomController extends Controller
{
 
     /**
     * @OA\Get(
     *      path="/api/waitingRoom",
     *      operationId="getWaitingRoomsList",
     *      tags={"Waiting Rooms"},
     *      summary="Get list of waiting rooms",
     *      description="Returns list of waiting rooms",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200),
     *              @OA\Property(
     *                  property="waiting_rooms",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="arrival_date", type="string", format="date"),
     *                      @OA\Property(property="departure_date", type="string", format="date"),
     *                      @OA\Property(property="doctor_id", type="integer"),
     *                      @OA\Property(property="patient_id", type="integer"),
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No waiting rooms found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=404),
     *              @OA\Property(property="message", type="string", example="No waiting rooms found")
     *          )
     *      )
     * )
     */
 
    public function index()
    {
        $waitingRooms = WaitingRoom::all();
 
        if ($waitingRooms->isEmpty()) {
            return response()->json(['status' => 404,
                                     'message' => __('messages.waiting_room').__('messages.not_found')], 404);
        }
 
        return response()->json(['status' => 200, 'waiting_rooms' => $waitingRooms], 200);
    }
 
 
     /**
      * Crée une nouvelle salle d'attente.
     * @OA\Post(
     *      path="/api/waitingRoom",
     *      operationId="storeWaitingRoom",
     *      tags={"Waiting Rooms"},
     *      summary="Create a new waiting room",
     *      description="Creates a new waiting room",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Waiting room data",
     *          @OA\JsonContent(
     *              required={"arrival_date", "doctor_id", "patient_id"},
     *              @OA\Property(property="arrival_date", type="string", format="date"),
     *              @OA\Property(property="departure_date", type="string", format="date"),
     *              @OA\Property(property="doctor_id", type="integer"),
     *              @OA\Property(property="patient_id", type="integer"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Waiting room created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=201),
     *              @OA\Property(property="message", type="string", example="Waiting room created successfully")
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
     *              @OA\Property(property="message", type="string", example="An error occurred while creating the waiting room")
     *          )
     *      )
     * )
     */
 
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'arrival_date' => 'required|date',
            'departure_date' => 'nullable|date|after:arrival_date',
            'doctor_id' => 'required|numeric|min:0',
            'patient_id' => 'required|numeric|min:0',
           
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'erreur' => $validator->messages()], 422);
        }
//TEST:  cle primaire: doctor id +patient id+arrival date:
 
// Récupération de tous les enregistrements existants
// Vérification s'il existe déjà un enregistrement pour le même patient_id et doctor_id et arrival_date:
    $exist=WaitingRoom::where('doctor_id',$request->doctor_id)->where('patient_id',$request->patient_id)->where('arrival_date',$request->arrival_date)->count()!=0;
 
    if ($exist==0) {
            return response()->json(['status' => 409,
                                    'message' => __('messages.waiting_room').__('messages.exist')], 409);
        }
   
        $waitingRoom = WaitingRoom::create($validator->validated());
 
        if ($waitingRoom) {
            return response()->json(['status' => 201,
                                    'message' => __('messages.waiting_room').__('messages.created')], 201);
        } else {
            return response()->json(['status' => 500,
                                    'message' => __('messages.waiting_room').__('messages.erreur')], 500);
        }
    }
 
      /**
    * Affiche une salle d'attente spécifique.
 
     * @OA\Get(
     *      path="/api/waitingRoom/{id}",
     *      operationId="getWaitingRoomById",
     *      tags={"Waiting Rooms"},
     *      summary="Get a specific waiting room",
     *      description="Returns details of a specific waiting room",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the waiting room to retrieve",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200),
     *              @OA\Property(property="message", type="string", example="Waiting room found successfully"),
     *              @OA\Property(
     *                  property="waiting_room",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", format="int64"),
     *                  @OA\Property(property="arrival_date", type="string", format="date"),
     *                  @OA\Property(property="departure_date", type="string", format="date"),
     *                  @OA\Property(property="doctor_id", type="integer"),
     *                  @OA\Property(property="patient_id", type="integer"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Waiting room not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=404),
     *              @OA\Property(property="message", type="string", example="Waiting room not found")
     *          )
     *      )
     * )
     */
 
    public function show(string $id)
    {
        $waitingRoom = WaitingRoom::find($id);
 
        if (!$waitingRoom) {
            return response()->json(['status' => 404,
                                     'message' => __('messages.waiting_room').__('messages.not_found')], 404);
        }
 
        return response()->json(['status' => 200, 'waiting_room' => $waitingRoom], 200);
    }
 
      /**
     * Met à jour une salle d'attente existante.
     * @OA\Put(
     *      path="/api/waitingRoom/{id}",
     *      operationId="updateWaitingRoom",
     *      tags={"Waiting Rooms"},
     *      summary="Update an existing waiting room",
     *      description="Updates an existing waiting room",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the waiting room to update",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Waiting room data",
     *          @OA\JsonContent(
     *              @OA\Property(property="arrival_date", type="string", format="date"),
     *              @OA\Property(property="departure_date", type="string", format="date"),
     *              @OA\Property(property="doctor_id", type="integer"),
     *              @OA\Property(property="patient_id", type="integer"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Waiting room updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200),
     *              @OA\Property(property="message", type="string", example="Waiting room updated successfully")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Waiting room not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=404),
     *              @OA\Property(property="message", type="string", example="Waiting room not found")
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
     *              @OA\Property(property="message", type="string", example="An error occurred while updating the waiting room")
     *          )
     *      )
     * )
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'arrival_date' => 'required|date',
            'departure_date' => 'nullable|date|after:arrival_date',
           
        ]);
 
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'erreur' => $validator->messages()], 422);
        }
 
        $waitingRoom = WaitingRoom::find($id);
 
        if (!$waitingRoom) {
            return response()->json(['status' => 404, 'message' => __('messages.waiting_room').__('messages.not_found')],404);
        }
 
        if ($request->doctor_id && $waitingRoom->doctor_id != $request->doctor_id || $request->patient_id && $waitingRoom->patient_id != $request->patient_id) {
            return response()->json(['status' => 404,
                                    'message' => __('messages.waiting_room').__('messages.erreur')], 404);
        }
 
        $updateResult=$waitingRoom->update($request->all());
        if (!$updateResult) {
            return response()->json(['status' => 500,
                                    'message' => __('messages.waiting_room').__('messages.erreur')], 500);
        }
 
        return response()->json(['status' => 200,
                                'message' => __('messages.waiting_room').__('messages.updated')], 200);
    }
  /**
     * Supprime une salle d'attente.
     * @OA\Delete(
     *      path="/api/waitingRoom/{id}",
     *      operationId="deleteWaitingRoom",
     *      tags={"Waiting Rooms"},
     *      summary="Delete a waiting room",
     *      description="Deletes a waiting room",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the waiting room to delete",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Waiting room deleted successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=201),
     *              @OA\Property(property="message", type="string", example="Waiting room deleted successfully")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Waiting room not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=404),
     *              @OA\Property(property="message", type="string", example="Waiting room not found")
     *          )
     *      )
     * )
     */
    public function destroy(string $id)
    {
        $waitingRoom = WaitingRoom::find($id);
 
        if (!$waitingRoom)
        {
            return response()->json(['status' => 404,
                                    'message' => __('messages.waiting_room').__('messages.not_found')], 404);
        }
 
        $waitingRoom->delete();
 
        return response()->json(['status' => 201,
                                 'message' => __('messages.waiting_room').__('messages.deleted')], 201);
 
    }
}