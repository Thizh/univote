namespace App\Http\Controllers;

use Illuminate\Http\Request;
use phpseclib3\Crypt\RSA;

class DecryptionController extends Controller
{
    public function decryptVote(Request $request)
    {
        // Retrieve encrypted data from the request
        $encryptedDataBase64 = $request->input('encrypted_data');
        
        // Decode the Base64 encoded encrypted data
        $encryptedData = base64_decode($encryptedDataBase64);
        
        // Retrieve the private key from the environment (or another secure location)
        $privateKey = env('PRIVATE_KEY'); // Add your private key in .env

        // Create an RSA object and load the private key
        $rsa = RSA::load($privateKey);
        
        // Decrypt the data using the private key
        $decryptedData = $rsa->decrypt($encryptedData);
        
        if ($decryptedData) {
            // Successfully decrypted, return the data
            $decryptedData = json_decode($decryptedData, true);
            return response()->json([
                'status' => 'success',
                'data' => $decryptedData,  // Return the decrypted user and vote details
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to decrypt the data.',
            ]);
        }
    }
}
