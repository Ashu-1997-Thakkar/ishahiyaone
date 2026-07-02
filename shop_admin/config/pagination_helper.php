<?php
/**
 * Pagination Helper
 * Generates consistent pagination HTML for the admin panel.
 */
function renderPagination(int $totalRows, int $limit, int $currentPage, string $module, string $search = "") {
    $totalPages = ceil($totalRows / $limit);
    if ($totalPages <= 1) return "";

    $html = '<nav aria-label="Page navigation" class="mt-4"><ul class="pagination justify-content-center">';
    
    // Previous Link
    $prevPage = ($currentPage > 1) ? $currentPage - 1 : 1;
    $html .= '<li class="page-item ' . ($currentPage == 1 ? 'disabled' : '') . '">';
    $html .= '<a class="page-link" href="javascript:void(0)" onclick="loadModule(\'' . $module . '\', ' . $prevPage . ')">Previous</a></li>';

    // Page Numbers
    for ($i = 1; $i <= $totalPages; $i++) {
        $html .= '<li class="page-item ' . ($i == $currentPage ? 'active' : '') . '">';
        $html .= '<a class="page-link" href="javascript:void(0)" onclick="loadModule(\'' . $module . '\', ' . $i . ')">' . $i . '</a></li>';
    }

    // Next Link
    $nextPage = ($currentPage < $totalPages) ? $currentPage + 1 : $totalPages;
    $html .= '<li class="page-item ' . ($currentPage == $totalPages ? 'disabled' : '') . '">';
    $html .= '<a class="page-link" href="javascript:void(0)" onclick="loadModule(\'' . $module . '\', ' . $nextPage . ')">Next</a></li>';

    $html .= '</ul></nav>';
    return $html;
}
?>
