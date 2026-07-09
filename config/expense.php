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
    */

    'max_bulk_create' => (int) env('EXPENSE_MAX_BULK_CREATE', 50),

    'bulk_chunk_size' => (int) env('EXPENSE_BULK_CHUNK_SIZE', 10),

];
