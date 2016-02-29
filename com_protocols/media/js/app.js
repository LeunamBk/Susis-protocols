// definition of the angular app modul
var app = angular.module('myApp', [])

app.controller('tasksController', function($scope, $http, $sce){

    getTask(); // Load all available tasks
    getYears(); // Load all available years with data for dropdown select
    function getTask(){
        $http.post('?option=com_protocols&task=request.protocolsByCategory').success(function(data){
            $scope.tasks = data;
        });
    };

    function getYears(){
        $http.post('?option=com_protocols&task=request.yearsDropDown').success(function(data){
            $scope.yearsList = data;
            $scope.option = $scope.yearsList[0].year;
        });
    };

    $scope.filterByTime = function(task){
        var context = $scope.data.selectedOption['id'];
        var month = $scope.data.selectedMonth['id'];
        var year = $scope.data.selectedYear['year'];
        if(year == "Alle") year = 0;

        $http.post('?option=com_protocols&task=request.protocolsByTime&mode='+context+'&year='+year+'&month='+month).success(function(data){
            $scope.tasks = data;
        });
    };

    $scope.toggleStatus = function(id){

        //highlight clicked feature
        $scope.idSelected = id;

        $http.post("?option=com_protocols&task=request.protocolText&id="+id).success(function(data){
            $scope.html = data.replace (/(^')|('$)/g, '');
            $scope.fullProtocol = $sce.trustAsHtml($scope.html);
            $scope.hideFullProtocol = false;
        });
    };

    $scope.search = function(){
        var context = $scope.data.selectedOption['id'];
        $http.post('?option=com_protocols&task=request.protocolsBySearch&mode='+context+"&search="+$scope.filterTasks).success(function(data){
            $scope.tasks = data.protocolsMeta;
            $scope.protocolsText = data.protocolsSearchSnippets;
            $scope.hideFullProtocol = true;
        });
    };

    $scope.getSnippet = function(snippet){
        if(snippet.length > 1){
            var concatSnippet = snippet[0];
            for(var i = 1; i < snippet.length; i++){
                concatSnippet += "<br><br>[...]<br><br>" + snippet[i];
            }
            return  $sce.trustAsHtml(concatSnippet);
        } else {
            return $sce.trustAsHtml(snippet[0])
        }
    };

    $scope.data = {
        availableOptions: [
            {id: '0', name: 'Alle'},
            {id: '1', name: 'MV'},
            {id: '2', name: 'Bauko'},
            {id: '3', name: 'Proko'},
            {id: '4', name: 'Sonstige'}
        ],
        selectedOption: {id: '0', name: 'Alle'}, // set default

        monthList: [
            {id: '0', name: 'Alle'},
            {id: '1', name: 'Januar'},
            {id: '2', name: 'Februar'},
            {id: '3', name: 'März'},
            {id: '4', name: 'April'},
            {id: '5', name: 'Mai'},
            {id: '6', name: 'Juni'},
            {id: '7', name: 'Juli'},
            {id: '8', name: 'August'},
            {id: '9', name: 'September'},
            {id: '10', name: 'Oktober'},
            {id: '11', name: 'November'},
            {id: '12', name: 'Dezember'}
        ],
        selectedMonth: {id: '0', name: 'Alle'}, // set default
        selectedYear: {year: 'Alle'} // set default

    };

    $scope.dataModel = {
        contextMap: {
            0: 'Alle',
            1: 'MV',
            2: 'Bauko',
            3: 'Proko'}

    };

    $scope.change = function(){
        var context = $scope.data.selectedOption['id'];
        $http.post('?option=com_protocols&task=request.protocolsByCategory&mode=' + context).success(function(data){
            $scope.tasks = data;
        });
    };


});
