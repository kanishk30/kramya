'use strict';

angular.module('KramyaApp').controller('CarouselDemoCtrl', function ($scope) {
  $scope.myInterval = 2500;
  $scope.noWrapSlides = false;
  $scope.numbers=4;
  $scope.texts=["India is among Asia's top three medical tourism destinations",'Bright Future For Medical Tourism - Industry Minister','WILL HEALTH TOURISM DISRUPT AMERICA’S HOSPITALS?','India Says: Bring Us Your Sick']
  $scope.providers= ['ABP Live','The Gleaner','Newsweek','CFR'];
  var slides = $scope.slides = [];
  $scope.addSlide = function(text , provider) {
    slides.push({
      text: text,
      providers: provider
    });
  };
  for (var i=0; i<$scope.numbers; i++) {
    $scope.addSlide($scope.texts[i] , $scope.providers[i]);
  }
});