<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Bulk expense create limits
    |--------------------------------------------------------------------------
    |
    | Maximum number of expense rows allowed in the multi-row create form.
    | Requests are sent in smaller chunks to avoid server POST size/timeouts.
    |
    | Keep bulk_chunk_size at or below 10 when the server has maxFileUploads=20
    | and users may attach an approval image on every row.
    |
    */

    'max_bulk_create' => (int) env('EXPENSE_MAX_BULK_CREATE', 50),

    'bulk_chunk_size' => (int) env('EXPENSE_BULK_CHUNK_SIZE', 10),

];
