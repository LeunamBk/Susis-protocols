<?php defined( '_JEXEC' ) or die( 'Restricted access' );

class ProtocolsControllerRequest extends JControllerLegacy
{

    public function yearsDropDown()
    {
        // call model and get data
        $model = $this->getModel();
        $years = $model->getYearsList();

        echo json_encode($years);

        // prevent joomla from returning footer and header html with data
        jexit();
    }

    public function protocolsByCategory()
    {

        // get mode argument from url
        $jinput = JFactory::getApplication()->input;
        $mode = $jinput->get('mode', '%', 'INT');

        // if 0 query for all
        if($mode == 0) $mode = '%';

        // call model and get data
        $model = $this->getModel();
        $pList = $model->getProtocolsByCategory($mode);

        // return data
        echo json_encode($pList);

        // prevent joomla from returning footer and header html with data
        jexit();
    }

    public function protocolsBySearch()
    {

        // get mode argument from url
        $jinput = JFactory::getApplication()->input;
        $mode = $jinput->get('mode', '%', 'INT');
        $search = $jinput->get('search', '', 'STRING');

        // if 0 query for all
        if($mode == 0) $mode = '%';

        // call model and get data
        $model = $this->getModel();
        $pList = $model->getProtocolsBySearch($mode, $search);

        // return data
        echo json_encode($pList);

        // prevent joomla from returning footer and header html with data
        jexit();
    }

    public function protocolsByTime()
    {

        // get mode argument from url
        $jinput = JFactory::getApplication()->input;
        $mode = $jinput->get('mode', '%', 'INT');
        $year = $jinput->get('year', '%', 'INT');
        $month = $jinput->get('month', '%', 'INT');

        // if 0 query for all
        if($mode == 0) $mode = '%';
        if($year == 0) $year = '%';
        if($month == 0) $month = '%';

        // call model and get data
        $model = $this->getModel();
        $pList = $model->getProtocolsByTime($mode, $year, $month);

        // return data
        echo json_encode($pList);

        // prevent joomla from returning footer and header html with data
        jexit();
    }

    public function protocolText(){

        // Request the selected id
        $jinput = JFactory::getApplication()->input;
        $id     = $jinput->get('id', 1, 'INT');

        // call model and get data
        $model = $this->getModel();
        $text = $model->getProtocolText($id);

        echo var_export($text);

        jexit();
    }

}