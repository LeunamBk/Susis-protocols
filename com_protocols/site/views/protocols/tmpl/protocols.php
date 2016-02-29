<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_protocols
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load assets
JHtml::_('stylesheet', JUri::root() . 'media/com_protocols/css/cssreset.css');
JHtml::_('stylesheet', JUri::root() . 'media/com_protocols/css/bootstrap.min.css');  // bootstrap v3
JHtml::_('stylesheet', JUri::root() . 'media/com_protocols/css/taskman.css');
JHtml::_('script', JUri::root() . 'media/com_protocols/js/angular.min.js');
JHtml::_('script', JUri::root() . 'media/com_protocols/js/app.js');

?>

<div ng-app="myApp">
    <div ng-controller="tasksController">

        <div class="row">
            <div class="container">
                <!--blockquote><h1><a href="#">Susis Protokolle</a></h1></blockquote-->
                <div class="col-sm-12">

                    <div class="widget-box" id="recent-box" ng-controller="tasksController">
                        <div class="widget-header header-color-blue">
                            <div class="row">

                                <div class="col-sm-3">
                                    <h4 class="bigger lighter">
                                        <i class="glyphicon glyphicon-align-justify"></i>&nbsp;
                                        Susis Protokolle
                                    </h4>
                                </div>

                                <div class="col-sm-2">
                                    <label for="mySelect" class="header-elements-margin">Context:</label>
                                    <select name="mySelect" id="mySelect" class=" header-elements-margin"
                                            ng-options="option.name for option in data.availableOptions track by option.id"
                                            ng-model="data.selectedOption"
                                            ng-change="change()"></select>
                                </div>

                                <div class="col-sm-3">
                                    <label for="mySelect" class="header-elements-margin">Timeframe:</label>
                                    <!--input type="month" class="header-elements-margin"
                                           ng-model="filterTime"
                                           ng-change="filterByTime()"-->

                                    <div>
                                        <select class="select-month header-elements-margin" name="select-month"
                                                ng-options="option.name for option in data.monthList track by option.id"
                                                ng-model="data.selectedMonth"
                                                ng-change="filterByTime()">
                                        </select>

                                        <select class="select-year header-elements-margin" name="select-year"
                                                ng-options="option.year for option in yearsList track by option.year"
                                                ng-model="data.selectedYear"
                                                ng-change="filterByTime()">
                                        </select>

                                    </div>
                                </div>

                                <!--div class="col-sm-3">
                                    <button ng-click="addNewClicked=!addNewClicked;" class="btn btn-sm btn-danger header-elements-margin"><i class="glyphicon  glyphicon-plus"></i>&nbsp;Add New Task</button>
                                </div-->
                                <div class="row">
                                    <div class="col-sm-3">

                                        <div class="input-group">
                                            <label class=" header-elements-margin" >Search:</label>
                                            <input type="text" ng-model="filterTasks" class="search header-elements-margin" ng-keyup="$event.keyCode == 13 && search()" placeholder="Filter Documents">
                                            <button type="submit" class="btn btn-default" name="submit" id="searchsubmit" value="Go" ng-click="search()">
						                    <span class="glyphicon glyphicon-search">
                                            </span>
                                            </button>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="widget-body ">
                            <!--form ng-init="addNewClicked=false; " ng-if="addNewClicked" id="newTaskForm" class="add-task">
                                <div class="form-actions">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="comment" ng-model="taskInput" placeholder="Add New Task" ng-focus="addNewClicked">
                                        <div class="input-group-btn">
                                            <button class="btn btn-default" type="submit" ng-click="addTask(taskInput)"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add New Task</button>
                                        </div>
                                    </div>
                                </div>
                            </form-->

                            <div class="col-sm-3">
                                <div class="task">
                                    <label ng-repeat="task in tasks | filter : filterTask" class="checkbox" ng-class="{selected: task.id === idSelected}" ng-click="toggleStatus(task.id,task.STATUS, task.TASK)">
                                        <span class="list-category">{{ dataModel.contextMap[task.context]}}</span><span class="list-date"><b>{{task.date}}</b></span>
                                        <!--a ng-click="deleteTask(task.ID)" class="pull-right"><i class="glyphicon glyphicon-trash"></i></a-->
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="protocol-textbox" ng-hide="hideFullProtocol">
                                    <div id="formater" style="visibility:hidden;"></div>
                                    <div ng-bind-html="fullProtocol"></div>
                                </div>

                                <div class="protocol-textbox" ng-repeat="snippet in protocolsText"  ng-hide="!hideFullProtocol">
                                    <div id="formater" style="visibility:hidden;"></div>
                                    <div ng-bind-html="getSnippet(snippet)"></div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>