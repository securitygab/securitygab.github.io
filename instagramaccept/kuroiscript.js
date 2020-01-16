function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

var Client = window.Crosis.Client;
var STATE_STOPPED = 0;
var STATE_STARTED = 1;

function start() {
  return _start.apply(this, arguments);
}

function _start() {
  _start = _asyncToGenerator(
  /*#__PURE__*/
  regeneratorRuntime.mark(function _callee() {
    var term, parts, _parts, slug, username, client, token, pkgChan, runChan, runningStarted;

    return regeneratorRuntime.wrap(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            term = createTerminal();
            startLoader(term, 'Follow @kuroi_dotsh on Twitter about updates...');
            parts = window.location.hostname.split(/\./).slice(0, 2);

            if (window.location.hostname === 'repl.run') {
              parts = ['blinkenlights', 'turbio'];
            }

            _parts = parts, slug = _parts[0], username = _parts[1];
            client = new Client(function (debug) {
              if (debug.type === 'breadcrumb') {
                Sentry.addBreadcrumb({
                  category: 'crosis',
                  message: debug.message,
                  data: debug.data
                });
              }
            });
            _context.next = 8;
            return fetchToken(username, slug);

          case 8:
            token = _context.sent;
            _context.prev = 9;
            _context.next = 12;
            return client.connect({
              token: token,
              urlOptions: {
                secure: true,
                port: '443'
              }
            });

          case 12:
            _context.next = 21;
            break;

          case 14:
            _context.prev = 14;
            _context.t0 = _context["catch"](9);
            stopLoader();
            logMessage(term, 'Unable to connect: ' + _context.t0.message);
            logMessage(term, 'Please try again later.');
            onExit(term);
            return _context.abrupt("return");

          case 21:
            client.on('close', function (_ref) {
              var expected = _ref.expected;

              if (!expected) {
                logMessage(term, 'Connection closed abruptly');
              }

              onExit(term);
            });
            startLoader(term, 'Installing Packages...');
            _context.prev = 23;
            _context.next = 26;
            return asyncOpenChan(client, {
              service: 'packager3'
            });

          case 26:
            pkgChan = _context.sent;
            pkgChan.on('command', function (cmd) {
              if (cmd.output) {
                logMessage(term, cmd.output);
              }
            });
            _context.next = 30;
            return pkgChan.request({
              packageInstall: {}
            });

          case 30:
            stopLoader();
            logMessage(term, 'Package install complete!');
            _context.next = 36;
            break;

          case 34:
            _context.prev = 34;
            _context.t1 = _context["catch"](23);

          case 36:
            startLoader(term, 'Starting Repl...');
            _context.next = 39;
            return getCodeRunner(client);

          case 39:
            runChan = _context.sent;
            runningStarted = false;
            runChan.on('command', function (cmd) {
              switch (cmd.body) {
                case 'output':
                  term.write(cmd.output);
                  return;

                case 'state':
                  if (cmd.state === STATE_STOPPED) {
                    if (runningStarted) {
                      // Interp sends STATE_STOPPED when it loads
                      onExit(term);
                      client.close();
                    }
                  } else {
                    runningStarted = true;
                  }

              }
            });
            term.on('resize', function (_ref2) {
              var cols = _ref2.cols,
                  rows = _ref2.rows;
              return runChan.send({
                resizeTerm: rows,
                cols: cols
              });
            });
            runChan.send({
              resizeTerm: {
                rows: term.rows,
                cols: term.cols
              }
            });
            runChan.send({
              saneTerm: {}
            });
            term.on('data', function (input) {
              return runChan.send({
                input: input
              });
            });
            stopLoader();
            term.reset();
            term.fit();
            runChan.send({
              runMain: {}
            });

          case 50:
          case "end":
            return _context.stop();
        }
      }
    }, _callee, null, [[9, 14], [23, 34]]);
  }));
  return _start.apply(this, arguments);
}

function asyncOpenChan(_x, _x2) {
  return _asyncOpenChan.apply(this, arguments);
}

function _asyncOpenChan() {
  _asyncOpenChan = _asyncToGenerator(
  /*#__PURE__*/
  regeneratorRuntime.mark(function _callee2(client, opts) {
    var chan;
    return regeneratorRuntime.wrap(function _callee2$(_context2) {
      while (1) {
        switch (_context2.prev = _context2.next) {
          case 0:
            chan = client.openChannel(opts);
            _context2.next = 3;
            return new Promise(function (res, rej) {
              chan.once('open', res);
              chan.once('error', rej);
            });

          case 3:
            return _context2.abrupt("return", chan);

          case 4:
          case "end":
            return _context2.stop();
        }
      }
    }, _callee2);
  }));
  return _asyncOpenChan.apply(this, arguments);
}

function getCodeRunner(_x3) {
  return _getCodeRunner.apply(this, arguments);
}

function _getCodeRunner() {
  _getCodeRunner = _asyncToGenerator(
  /*#__PURE__*/
  regeneratorRuntime.mark(function _callee3(client) {
    var filer, readReplit;
    return regeneratorRuntime.wrap(function _callee3$(_context3) {
      while (1) {
        switch (_context3.prev = _context3.next) {
          case 0:
            _context3.next = 2;
            return asyncOpenChan(client, {
              service: 'files'
            });

          case 2:
            filer = _context3.sent;
            _context3.next = 5;
            return filer.request({
              read: {
                path: '.replit'
              }
            });

          case 5:
            readReplit = _context3.sent;

            if (!readReplit.file) {
              _context3.next = 8;
              break;
            }

            return _context3.abrupt("return", asyncOpenChan(client, {
              service: 'run2'
            }));

          case 8:
            _context3.prev = 8;
            _context3.next = 11;
            return asyncOpenChan(client, {
              service: 'interp2'
            });

          case 11:
            return _context3.abrupt("return", _context3.sent);

          case 14:
            _context3.prev = 14;
            _context3.t0 = _context3["catch"](8);
            return _context3.abrupt("return", asyncOpenChan(client, {
              service: 'run2'
            }));

          case 17:
          case "end":
            return _context3.stop();
        }
      }
    }, _callee3, null, [[8, 14]]);
  }));
  return _getCodeRunner.apply(this, arguments);
}

function fetchToken(_x4, _x5) {
  return _fetchToken.apply(this, arguments);
} // Gets a captcha string


function _fetchToken() {
  _fetchToken = _asyncToGenerator(
  /*#__PURE__*/
  regeneratorRuntime.mark(function _callee4(username, slug) {
    var url, captcha, res;
    return regeneratorRuntime.wrap(function _callee4$(_context4) {
      while (1) {
        switch (_context4.prev = _context4.next) {
          case 0:
            url = 'https://repl.it/data/repls/@' + username + '/' + slug + '/gen_repl_run_token';
            _context4.next = 3;
            return executeCaptcha();

          case 3:
            captcha = _context4.sent;
            _context4.next = 6;
            return fetch(url, {
              credentials: 'same-origin',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              method: 'post',
              body: 'format=pbuf&captcha=' + captcha
            });

          case 6:
            res = _context4.sent;
            return _context4.abrupt("return", res.json());

          case 8:
          case "end":
            return _context4.stop();
        }
      }
    }, _callee4);
  }));
  return _fetchToken.apply(this, arguments);
}

function executeCaptcha() {
  return _executeCaptcha.apply(this, arguments);
}

function _executeCaptcha() {
  _executeCaptcha = _asyncToGenerator(
  /*#__PURE__*/
  regeneratorRuntime.mark(function _callee5() {
    return regeneratorRuntime.wrap(function _callee5$(_context5) {
      while (1) {
        switch (_context5.prev = _context5.next) {
          case 0:
            return _context5.abrupt("return", new Promise(function (resolve, reject) {
              window.grecaptcha.ready(function () {
                try {
                  window.grecaptcha.execute('6Lc7fZQUAAAAAIXMD8AonuuleBX0P3hS2XW364Ms', {
                    action: 'replrun'
                  }).then(function (token) {
                    resolve(token);
                  }, // it's a fake promise that doesn't implement .catch, but
                  // it does implement .then second arg onReject
                  function (e) {
                    reject(e);
                  });
                } catch (e) {
                  // usually errors are thrown within execute in a non-promise like fashion
                  // and the onReject function passed to then is not called
                  reject(e);
                }
              });
            }));

          case 1:
          case "end":
            return _context5.stop();
        }
      }
    }, _callee5);
  }));
  return _executeCaptcha.apply(this, arguments);
}

var didExit = false;

function onExit(term) {
  if (didExit) {
    return;
  }

  didExit = 1;
  logMessage(term, '');
  var info = document.getElementById('restart-bar');
  info.style = '';
}

var loaderInterval = null;
var loaderMessage = '';

function startLoader(term, message) {
  if (message === void 0) {
    message = loaderMessage;
  }

  loaderMessage = message;

  if (loaderInterval) {
    stopLoader();
  }

  var counter = 0;
  var loadingChars = ['|', '/', '-', '\\'];
  loaderInterval = setInterval(function () {
    counter++;
    var spinner = loadingChars[counter % loadingChars.length];
    term.write(' \r\x1B[K' + spinner + ' ' + loaderMessage);
  }, 80);
}

function stopLoader() {
  clearInterval(loaderInterval);
  loaderInterval = null;
}

function createTerminal() {
  var terminalContainer = document.getElementById('target');
  var term = new Terminal({
    cols: 80,
    rows: 20,
    experimentalCharAtlas: 'dynamic',
    fontFamily: 'Monaco, "Ubuntu Mono", "Courier New", Courier, replit-icons, monospace'
  }); // Debug

  window.term = term;
  term.attachCustomKeyEventHandler(function () {
    return true;
  });
  window.Terminal.applyAddon(window.fullscreen);
  window.Terminal.applyAddon(window.fit);
  term.open(terminalContainer);
  term.focus();
  term.fit();

  document.body.onresize = function () {
    return term.fit();
  };

  return term;
}

function logMessage(term, text) {
  var crln = text.replace(/\n/g, '\r\n');

  if (crln.slice(-2) !== '\r\n') {
    crln += '\r\n';
  }

  term.write('\r\x1B[K' + crln);

  if (loaderInterval) {
    startLoader(term);
  }
}

start();
