use MongoDB\BSON\ObjectId;
use App\Models\Location;

class LocationController extends Controller
{
    protected $connection = 'mongodb';
    protected $collection = 'location';

    public function fetchObjectAddress($locationId)
    {
        try {
            // Create an ObjectId instance from the provided locationId
            $objectId = new ObjectId($locationId);
            console.log($objectId)
            // Fetch location details from the database based on the converted ObjectId
            $location = Location::find($objectId);

            if ($location) {
                // Construct address string
                $address = $location->rua . ', ' . $location->freguesia . ', ' . $location->municipio . ', ' . $location->distrito . ', ' . $location->codigo_postal . ', ' . $location->pais;

                return $address; // Return address string
            } else {
                return 'Location not found'; // Return error message if location not found
            }
        } catch (\Exception $e) {
            return 'Invalid location ID format'; // Return error message for invalid location ID format
        }
    }
}
