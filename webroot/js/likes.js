/**
 * @fileoverview Likes Javascript
 * @author nakajimashouhei@gmail.com (Shohei Nakajima)
 * @author exkazuu@gmail.com (Kazunori Sakamoto)
 */


(function() {
  /**
   * Likes Service Javascript
   *
   * @param {string} Controller name
   * @param {function('$q')} Controller
   */
  NetCommonsApp.factory('LikesLoad', ['$q', 'NC3_URL', function($q, NC3_URL) {
    return function(data) {
      return request($q, NC3_URL, data, true);
    };
  }]);

  NetCommonsApp.factory('LikesSave', ['$q', 'NC3_URL', function($q, NC3_URL) {
    return function(data) {
      return request($q, NC3_URL, data, false);
    };
  }]);

  function request($q, NC3_URL, params, isLoad) {
    var deferred = $q.defer();
    var promise = deferred.promise;

    $.ajax({
      url: NC3_URL + '/net_commons/net_commons/csrfToken.json', cache: false, success: function(data) {
        var url = NC3_URL;
        params = Object.assign({}, params);
        if (isLoad) {
          params._Token = params.load._Token;
          url += '/likes/likes/load.json';
          delete params.LikesUser;
        } else {
          params._Token = params.save._Token;
          url += '/likes/likes/save.json';
        }
        delete params.load;
        delete params.save;

        var token = data;
        params._Token.key = token.data._Token.key;
        $.ajax({
          url: url,
          method: 'POST',
          cache: false,
          data: $.param({data: params}),
          contentType: 'application/x-www-form-urlencoded',
          success: function(data) {
            deferred.resolve(data);
          },
          error: function(jqXHR) {
            deferred.reject(jqXHR.responseText, jqXHR.status);
          }
        });
      }, error: function(jqXHR) {
        deferred.reject(jqXHR.responseText, jqXHR.status);
      }
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
  }
})();

/**
 * Likes Controller Javascript
 *
 * @param {string} Controller name
 * @param {function($scope, LikesLoad, LikesSave)} Controller
 */
NetCommonsApp.controller('Likes', ['$scope', 'LikesLoad', 'LikesSave', function($scope, LikesLoad, LikesSave) {

  /**
   * Request parameters
   *
   * @type {object}
   */
  $scope.data = null;

  /**
   * Options parameters
   *   - disabled
   *   - likeCounts
   *   - unlikeCounts
   *
   * @type {object}
   */
  $scope.options = null;

  /**
   * initialize
   *   - disabled
   *   - likeCounts
   *   - unlikeCounts
   *
   * @return {void}
   */
  $scope.initialize = function(data, options) {
    $scope.data = data;
    $scope.options = options;
    $scope.options.disabled = true;

    LikesLoad($scope.data)
      .success(function(data) {
        $scope.options.disabled = data.disabled;
        $scope.options.likeCount = data.likeCount;
        $scope.options.unlikeCount = data.unlikeCount;
      });
  };

  /**
   * save
   *
   * @return {void}
   */
  $scope.save = function(isLiked) {
    $scope.data.LikesUser.is_liked = isLiked;
    if ($scope.options.disabled) {
      return;
    }
    $scope.options.disabled = true;
    $scope.sending = true;

    LikesSave($scope.data)
      .success(function() {
        $scope.sending = false;
        if (isLiked) {
          $scope.options.likeCount += 1;
        } else {
          $scope.options.unlikeCount += 1;
        }
      })
      .error(function() {
        $scope.sending = false;
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
