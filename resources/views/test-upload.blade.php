<!DOCTYPE html>
<html>
<head>
    <title>Test Backblaze Upload</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h2>Test Backblaze File Upload</h2>

    <form id="uploadForm" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Upload File</button>
    </form>

    <div id="result"></div>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const resultDiv = document.getElementById('result');

            resultDiv.innerHTML = 'Uploading...';

            fetch('/test-backblaze', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = `
                        <h3>Upload Successful!</h3>
                        <p><strong>URL:</strong> <a href="${data.data.url}" target="_blank">${data.data.url}</a></p>
                        <p><strong>Path:</strong> ${data.data.path}</p>
                        <p><strong>Size:</strong> ${(data.data.size / 1024).toFixed(2)} KB</p>
                        <p><strong>Type:</strong> ${data.data.type}</p>
                        <p><strong>Original Name:</strong> ${data.data.original_name}</p>
                    `;
                } else {
                    resultDiv.innerHTML = `<p style="color: red;">Error: ${data.error}</p>`;
                }
            })
            .catch(error => {
                resultDiv.innerHTML = `<p style="color: red;">Upload failed: ${error.message}</p>`;
            });
        });
    </script>
</body>
</html>
