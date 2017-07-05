<html ng-app="feedReaderApp">
<head>
    <meta charset="utf-8">
    <title>Feeds-listener</title>
</head>
<body>

<div ng-controller="FeedsListController">

    <label>Autoupdate:
        <input type="checkbox" ng-model="autoupdate" ng-click="getFeeds()">
    </label>

    <ul>
        <li ng-repeat="feed in feeds">
            <span>({{feed.provider}})</span>
            <span>{{feed.date}}</span><br>
            <span>{{feed.text}}</span>
        </li>
    </ul>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>

<script>

    var feedReaderApp = angular.module('feedReaderApp', []);

    feedReaderApp.controller('FeedsListController', function FeedsListController($scope, $http, $timeout) {

        $scope.feeds = [];
        $scope.autoupdate = false;

        $scope.getFeeds = function () {

            if (!$scope.autoupdate) return;

            $http.get('backend.php').then(
                function (response) {
                    $scope.feeds = response.data;
                });

            $timeout(function () {
                $scope.getFeeds();
            }, 10000);

        };

    });

</script>

</body>
</html>


