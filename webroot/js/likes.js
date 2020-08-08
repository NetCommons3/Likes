/**
 * @fileoverview Likes Javascript
 * @author nakajimashouhei@gmail.com (Shohei Nakajima)
 * @author exkazuu@willbooster.com (Kazunori Sakamoto)
 */


/**
 * Likes Service Javascript
 *
 * @param {string} Controller name
 * @param {function('$http', '$q')} Controller
 */
NetCommonsApp.factory('LikesSave', ['$http', '$q', 'NC3_URL', function($http, $q, NC3_URL) {
  return function(data) {
    var deferred = $q.defer();
    var promise = deferred.promise;

    $http.get(NC3_URL + '/net_commons/net_commons/csrfToken.json')
        .then(function(response) {
          var token = response.data;
          data._Token.key = token.data._Token.key;

          // POSTリクエスト
          $http.post(
          NC3_URL + '/likes/likes/save.json',
          $.param({_method: 'POST', data: data}),
              {
                cache: false,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
              }
          ).then(
              function(response) {
                // success condition
                var data = response.data;
                deferred.resolve(data);
              },
              function(response) {
                // error condition
                var data = response.data;
                var status = response.status;
                deferred.reject(data, status);
              });
        },
        function(response) {
          // Token error condition
          var data = response.data;
          var status = response.status;
          deferred.reject(data, status);
        });

    promise.success = function(fn) {
      promise.then(fn);
      return promise;
    };

    promise.error = function(fn) {
      promise.then(null, fn);
      return promise;
    };

    return promise;
  };
}]);


/**
 * Likes Controller Javascript
 *
 * @param {string} Controller name
 * @param {function($scope, LikesSave)} Controller
 */
NetCommonsApp.controller('Likes', ['$scope', 'LikesSave', function($scope, LikesSave) {

  /**
   * Request parameters
   *
   * @type {object}
   */
  $scope.data = null;

  /**
   * initialize
   *   - disabled
   *   - likeCounts
   *   - unlikeCounts
   *
   * @return {void}
   */
  $scope.initialize = function(data) {
    $scope.data = data;
  };

  /**
   * save
   *
   * @return {void}
   */
  $scope.save = function(isLiked, condsStr) {
    var queryPrefix = '.' + condsStr;
    var aDisplay = $(queryPrefix + ' > a').css('display');
    var spanDisplay = $(queryPrefix + ' > span').css('display');

    $(queryPrefix + ' > a').css('display', 'none');
    $(queryPrefix + ' > span').css('display', '');

    $scope.data.LikesUser.is_liked = isLiked;
    LikesSave($scope.data)
        .success(function() {
          var $counts;
          if (isLiked) {
            $counts = $(queryPrefix + ' .like-count');
          } else {
            $counts = $(queryPrefix + ' .unlike-count');
          }
          $counts.each(function() { $(this).text(Number($(this).text()) + 1); });
        })
        .error(function() {
          $(queryPrefix + ' > a').css('display', aDisplay);
          $(queryPrefix + ' > span').css('display', spanDisplay);
        });
  };
}]);


/**
 * LikeSettings Controller Javascript
 *
 * @param {string} Controller name
 * @param {function($scope)} Controller
 */
NetCommonsApp.controller('LikeSettings', ['$scope', function($scope) {

  /**
   * initialize
   *   - useLikeDomId
   *   - useUnlikeDomId
   *
   * @return {void}
   */
  $scope.initialize = function(useLikeDomId, useUnlikeDomId) {
    $scope.useLikeDomId = useLikeDomId;
    $scope.useUnlikeDomId = useUnlikeDomId;
  };

  /**
   * Use like button
   *
   * @return {void}
   */
  $scope.useLike = function() {
    var likeElement = $('#' + $scope.useLikeDomId);
    var unlikeElement = $('#' + $scope.useUnlikeDomId);

    if (likeElement[0].checked) {
      unlikeElement[0].disabled = false;
    } else {
      unlikeElement[0].disabled = true;
      unlikeElement[0].checked = false;
    }
  };
}]);
