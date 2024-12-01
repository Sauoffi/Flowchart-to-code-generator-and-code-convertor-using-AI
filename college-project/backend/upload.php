<?php

function TempUpload($fileTmpPath, $originalFileName, $fileMimeType) {
    $url = 'https://tmpfiles.org/api/v1/upload';

    // Initialize cURL
    $ch = curl_init();

    // Prepare file for upload using the original file name and MIME type
    $cfile = new CURLFile($fileTmpPath, $fileMimeType, $originalFileName);

    $data = array('file' => $cfile);

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL request
    $response = curl_exec($ch);

    // Check if any errors occurred
    if (curl_errno($ch)) {
        return 'ERROR_UPLOADING';
    }

    curl_close($ch);

    // Decode the JSON response
    $responseData = json_decode($response, true);

    if (isset($responseData['data']['url'])) {
        // Return the direct download link
        $imgUri = $responseData['data']['url'];
        $finalUrl = str_replace('https://tmpfiles.org/', 'https://tmpfiles.org/dl/', $imgUri);
        return $finalUrl;
    } else {
        return 'ERROR_UPLOADING';
    }
}

if ($_FILES['file']['name']) {
    // Get the file details
    $fileTmpPath = $_FILES["file"]["tmp_name"];
    $fileName = $_FILES["file"]["name"];
    $fileType = $_FILES["file"]["type"];
    
    // Allow only image file types
    $allowTypes = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');
    
    if (in_array($fileType, $allowTypes)) {
        // Call the TempUpload function to upload the file to tmpfiles.org
        $uploadedUrl = TempUpload($fileTmpPath, $fileName, $fileType);

        // Check if the file was successfully uploaded
        if ($uploadedUrl !== 'ERROR_UPLOADING') {
            echo $uploadedUrl;
        } else {
            echo "File upload failed, please try again.";
        }
    } else {
        echo "Sorry, only JPG, JPEG, PNG, & GIF files are allowed.";
    }
} else {
    echo "No file uploaded.";
}
