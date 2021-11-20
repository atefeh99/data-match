<?php


namespace App\Modules\ExportCsv;


class Transfer
{

    public function export()
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://gnaf-dev.map.ir/transfer/export?table=mym_864e44c99c3b4df87769d174a3476b5c&query=select*from%2520mym_864e44c99c3b4df87769d174a3476b5c&format=csv',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:94.0) Gecko/20100101 Firefox/94.0',
                'Accept: */*',
                'Accept-Language: en-US,en;q=0.5',
                'token: eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjVlY2RjNTA1MDA0MjcyY2I5Mjg2NDM2NmQ0MWEzMGI3YTQ0Yzk0OGJhMWNlNGZjYjRiYjQ2YTQ2NjE2Y2MzNTBiNTNhNjg5MzVhNTlmZjQ2In0.eyJhdWQiOiIxIiwianRpIjoiNWVjZGM1MDUwMDQyNzJjYjkyODY0MzY2ZDQxYTMwYjdhNDRjOTQ4YmExY2U0ZmNiNGJiNDZhNDY2MTZjYzM1MGI1M2E2ODkzNWE1OWZmNDYiLCJpYXQiOjE2MzY4ODMxNjUsIm5iZiI6MTYzNjg4MzE2NSwiZXhwIjoxNjM2ODg2NzY1LCJzdWIiOiI5YmY2NjkyOS0xOWMxLTRiOWUtODFiNS03Y2Y5OThiN2I0YjIiLCJzY29wZXMiOlsiYmFzaWMiLCJteTphZG1pbiJdfQ.uFeo6yKJx2ICGs9EimQc0xe39MabBmpJNgyky3H0wqRVOdssf12_VNKKaP9os9E57TUgWLs92A4gmmX2TjulUsXG6eZeXlO4mgiBxcCMpk_gwDhtbdYm9Q3XntQC0et9LBEOtXh83DeX34LudAJIR7hJZBiCRqPWJINq3iQWqdpT92nF3zY7FENkR-p-qUc0NDFqf2WNu3msWHPNJ9DYZ1a9RBPZrZWIjepGEXir3vbK65O9UkDoNJTgRHxnF12fFWOpSZ7856cJln1nPKY4dPHwNsadA9kdnymMgMcBLYonOvhFea7UVrmCmJRpbmeAJ9BY0lJxxW_lwH99v4IzEw',
                'x-api-key: eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ijc4ZGQ1MTRmMjNkYTk2NmUxZWQ4MmU3ZGFhNDU3NmU0OGIyNTA5Y2QyN2VlNWY3YTcyNGE1MDhhYTE0MDliYmI3YmU3ZTRlYjE4MTRiOTdlIn0.eyJhdWQiOiIxNDkiLCJqdGkiOiI3OGRkNTE0ZjIzZGE5NjZlMWVkODJlN2RhYTQ1NzZlNDhiMjUwOWNkMjdlZTVmN2E3MjRhNTA4YWExNDA5YmJiN2JlN2U0ZWIxODE0Yjk3ZSIsImlhdCI6MTYxNDY5MTAyMiwibmJmIjoxNjE0NjkxMDIyLCJleHAiOjE2MTcxOTMwMjIsInN1YiI6IiIsInNjb3BlcyI6WyJiYXNpYyJdfQ.nUJZ6yinVoYvi9ryiXwhgKsSBXoNiiSR_aR_z2-x0UMoVbdQhwJZFP7G4Kxu36qAlgypdDvuu0FBgig_m_N45f7P6APRfpfWgo1BUUzqCUCr0O47GsPz4TZw5UHtRtlEVTbVi7D3286i7uiG1xz7DhdgEQYUwexTY1XnTZEsZB2u6tTBQm9IYTsROyGUF_aMfZQdWAuxArhpGYTqAZdCed8m5mY1JtGX7W92yeFWDni08DmpWESmDB83b4I6yND_eNoaYZFQheXE3uI2XrHeWNnQX5Hctp8hY94y7xZmKajw1iV_GQbI4cSgmDm4g5f31-_wdIKkLQiTIgljlVYbSg',
                'Connection: keep-alive',
                'Referer: https://my-dev.map.ir/datasets/edit?id=77ed9463-275b-43b6-a8c8-75cc081ae076&table_name=mym_864e44c99c3b4df87769d174a3476b5c',
                'Cookie: _ga=GA1.2.920147502.1613890149; G_ENABLED_IDPS=google; _ga_968CSJHV95=GS1.1.1636879288.5.0.1636879288.60; _gid=GA1.2.536606181.1636795397',
                'Sec-Fetch-Dest: empty',
                'Sec-Fetch-Mode: cors',
                'Sec-Fetch-Site: same-origin',
                'TE: trailers'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

    }
}
