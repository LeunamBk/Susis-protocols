<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_protocols
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HelloWorld Model
 *
 * @since  0.0.1
 */
class ProtocolsModelProtocols extends JModelItem
{

    /**
     * Method to get a table object, load it if necessary.
     *
     * @param   string  $type    The table name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  JTable  A JTable object
     *
     * @since   1.6
     */
    public function getTable($type = 'Protocols', $prefix = 'ProtocolsTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }


    public function getYearsList()
    {
        // get a db connection.
        $db = JFactory::getDbo();

        // Create a new query object.
        $query = $db->getQuery(true);

        // query for data
        $query
            ->select("DISTINCT DATE_FORMAT(date,'%Y') as year")
            ->from("#__susidocs")
            ->order("DATE_FORMAT(date,'%Y') desc");

        // load and fetch data
        $db->setQuery((string) $query);
        $years = $db->loadObjectList();

        // push all data value entry
        array_unshift($years, array("year" => "Alle"));

        return $years;
    }


    /**
     * Method to get a list with metainformation
     * to each protocol in the requested context.
     *
     * @return  array.
     */
    public function getProtocolsByCategory($mode = '%')
    {

        // Get a db connection.
        $db = JFactory::getDbo();

        // Create a new query object.
        $query = $db->getQuery(true);

        // Query for data
        $query
            ->select("id, DATE_FORMAT(date,'%d-%m-%Y') as date, context")
            ->from("#__susidocs")
            ->where("context LIKE '$mode'")
            ->order("DATE_FORMAT(date,'%Y-%m-%d') desc");

        // Load and fetch data
        $db->setQuery((string) $query);
        $protocols = $db->loadObjectList();

        return $protocols;

    }

    public function getProtocolsByTime($mode, $year, $month)
    {
        // Get a db connection.
        $db = JFactory::getDbo();

        // Create a new query object.
        $query = $db->getQuery(true);

        // Query for data
        $query
            ->select("id, DATE_FORMAT(date,'%d-%m-%Y') as date, context")
            ->from("#__susidocs")
            ->where("context LIKE '$mode'")
            ->where("DATE_FORMAT(date,'%Y') LIKE '$year'")
            ->where("MONTH(date) LIKE '$month'")
            ->order("DATE_FORMAT(date,'%Y-%m-%d') desc");

        // Load and fetch data
        $db->setQuery((string) $query);
        $protocols = $db->loadObjectList();

        return $protocols;

    }


    public function getProtocolsBySearch($mode, $search)
    {

        // Get a db connection.
        $db = JFactory::getDbo();

        // Create a new query object.
        $query = $db->getQuery(true);

        // Query for data

        $query
            ->select("id, DATE_FORMAT(date,'%d-%m-%Y') as date, context, body")
            ->from("#__susidocs")
            ->where("context LIKE '$mode' and body LIKE '%$search%'")
            ->order("DATE_FORMAT(date,'%Y-%m-%d') desc");


        // Load and fetch data
        $db->setQuery((string) $query);
        $protocols = $db->loadObjectList();

        if(is_null($protocols)) return;

        // get paragraph of all matches which include search string
        $protocolsSearchSnippets = self::getSearchTextSnippets($protocols, $search);

        // exclude protocols body text from results
        $protocolsMeta = self::removeBodyTextFromResultset($protocols);

        return compact('protocolsMeta', 'protocolsSearchSnippets');

    }

    public function getProtocolText($id = 1)
    {

        // get susi table instance in
        // NOTE: in comparison to the above methods,
        // this approach is used for returning only one row
        $table = $this->getTable();

        // load data
        $table->load($id);

        return $table->body;

    }

    private static function getSearchTextSnippets($protocols, $search){


        // IMPORTANT: ensure proper string encoding!
        $search = utf8_encode($search);

        $protocolsSearchSnippets = array();

        foreach($protocols as $protocol){

            $DOM = self::loadHtml($protocol->body);
            $snippets = array();

            //get and iterate over all p html paragraphs
            $paras = $DOM->getElementsByTagName('p');
            foreach ($paras as $para) {
                $textContent = $para->textContent;

                // search for search string in paragraph -> case insesitive, for sensitive see strpos()
                if (stripos($textContent, $search) !== FALSE) {
                    // surround search string with span
                    $searchP = self::spanSearchString($textContent, $search);

                    array_push($snippets, utf8_decode($searchP));
                }
            }

            if(!empty($snippets)){
                $protocolsSearchSnippets[$protocol->id] = $snippets;
            }
        }

        return $protocolsSearchSnippets;
    }

    private static function loadHtml($html, $suppressWarnings = TRUE){
        $DOM = new DOMDocument;

        // if xml html string is not well formed e.g. opening tags without closing tags,
        // which is not a problem in modern browsers, e.g. <br> tag instead of <br/> load html
        // throws warnings, this is surpressed by the underlying routine
        if($suppressWarnings){
            // modify state
            $libxml_previous_state = libxml_use_internal_errors(true);

            $DOM->loadHTML($html);

            // handle errors
            libxml_clear_errors();

            // restore
            libxml_use_internal_errors($libxml_previous_state);
        } else {
            $DOM->loadHTML($html);
        }
        return $DOM;
    }

    private static function spanSearchString($text, $search){
        $spanOpen = "<span class='searchString'>";
        $begin = stripos($text, $search);
        $end = $begin + strlen($search) + strlen($spanOpen);
        $text = substr_replace($text, $spanOpen, $begin, 0);
        $text = substr_replace($text, "</span>", $end, 0);
        return $text;
    }

    private static function removeBodyTextFromResultset($protocols){

        $protocolsMeta = array();
        foreach($protocols as $key => $protocol){
            $line = array('id' => $protocol->id, 'date' => $protocol->date, 'context' => $protocol->context);
            array_push($protocolsMeta, $line);
        }

        return $protocolsMeta;
    }

}