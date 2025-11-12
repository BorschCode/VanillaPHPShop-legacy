<?php
/**
 * Class Pagination
 * Created by PhpStorm.
 * Date: 08.12.2017
 * Time: 15:58
 * Class for generating paginated navigation
 */


class Pagination
{

    /**
     *
     * @var Number of navigation links to display
     *
     */
    private $max = 10;

    /**
     *
     * @var GET key used for the page number in the URL
     *
     */
    private $index = 'page';

    /**
     *
     * @var Current page number
     *
     */
    private $current_page;

    /**
     *
     * @var Total number of records
     *
     */
    private $total;

    /**
     *
     * @var Records per page (Limit)
     *
     */
    private $limit;

    /**
     *
     * @var Total number of pages
     *
     */
    private $amount;

    /**
     * Initializes necessary data for navigation
     * @param integer $total     - total number of records
     * @param integer $currentPage - current page number
     * @param integer $limit     - number of records per page
     * @param string $index      - GET key for page number in URL
     *
     * @return void
     */
    public function __construct($total, $currentPage, $limit, $index)
    {
        # Set the total number of records
        $this->total = $total;

        # Set the limit of records per page
        $this->limit = $limit;

        # Set the key in the URL
        $this->index = $index;

        # Calculate and set the total number of pages
        $this->amount = $this->amount();

        # Set the current page number
        $this->setCurrentPage($currentPage);
    }

    /**
     * Generates and returns the navigation links
     *
     * @return string HTML code with navigation links
     */
    public function get()
    {
        # Variable to store links
        $links = null;

        # Get limits for the loop (start and end page numbers)
        $limits = $this->limits();

        $html = '<ul class="pagination">';
        # Generate the links
        for ($page = $limits[0]; $page <= $limits[1]; $page++) {
            # If this is the current page, no link is generated and the 'active' class is added
            if ($page == $this->current_page) {
                $links .= '<li class="active"><a href="#">' . $page . '</a></li>';
            } else {
                # Otherwise, generate the link
                $links .= $this->generateHtml($page);
            }
        }

        # If links were created
        if (!is_null($links)) {
            # If the current page is not the first page (show 'To First' link)
            if ($this->current_page > 1)
                # Create 'To First' link (using '<')
                $links = $this->generateHtml(1, '&lt;') . $links;

            # If the current page is not the last page (show 'To Last' link)
            if ($this->current_page < $this->amount)
                # Create 'To Last' link (using '>')
                $links .= $this->generateHtml($this->amount, '&gt;');
        }

        $html .= $links . '</ul>';

        # Return the resulting HTML
        return $html;
    }

    /**
     * Generates the HTML code for a single link
     * @param integer $page - page number
     * @param string|null $text - optional text for the link (e.g., '>' or '<')
     *
     * @return string
     */
    private function generateHtml($page, $text = null)
    {
        # If link text is not specified
        if (!$text)
            # Set the text to the page number
            $text = $page;

        // Clean current URI by removing previous page index (e.g., /page-1)
        $currentURI = rtrim($_SERVER['REQUEST_URI'], '/') . '/';
        $currentURI = preg_replace('~/page-[0-9]+~', '', $currentURI);

        # Formulate and return the HTML code for the link
        return
            '<li><a href="' . $currentURI . $this->index . $page . '">' . $text . '</a></li>';
    }

    /**
     * Determines the start and end pages for the visible block of links
     *
     * @return array Array with start and end page numbers
     */
    private function limits()
    {
        # Calculate links to the left (so the active link is in the middle)
        $left = $this->current_page - round($this->max / 2);

        # Calculate the start of the count (minimum is 1)
        $start = $left > 0 ? $left : 1;

        # If there are enough pages ahead to show $this->max links
        if ($start + $this->max <= $this->amount)
            # Set the end of the loop forward by $this->max pages
            $end = $start > 1 ? $start + $this->max : $this->max;
        else {
            # End is the total number of pages
            $end = $this->amount;

            # Start is the end minus $this->max (minimum is 1)
            $start = $this->amount - $this->max > 0 ? $this->amount - $this->max : 1;
        }

        # Return the start and end pages
        return
            array($start, $end);
    }

    /**
     * Sets and validates the current page number
     *
     * @param integer $currentPage The proposed current page number
     * @return void
     */
    private function setCurrentPage($currentPage)
    {
        # Get the page number
        $this->current_page = $currentPage;

        # If the current page is greater than zero
        if ($this->current_page > 0) {
            # If current page is greater than the total number of pages
            if ($this->current_page > $this->amount)
                # Set the page to the last one
                $this->current_page = $this->amount;
        } else
            # If invalid or zero, set the page to the first one
            $this->current_page = 1;
    }

    /**
     * Calculates the total number of pages
     *
     * @return int Total number of pages
     */
    private function amount()
    {
        # Divide total records by limit and return (rounded up for partial pages)
        return ceil($this->total / $this->limit);
    }

}