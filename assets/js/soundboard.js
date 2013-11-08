(function(){
    var viewModel,
        ctx,
        buf,
        mainVol;

    $(function(){
        viewModel = new SoundBoardModel(getClips);
        ko.applyBindings(viewModel);

        if (jPhong.deviceInfo.isIOS()) {
            $('#iphoneKludge').show();
        }

        if (jPhong.deviceInfo.supportsAudioContext()){
            initAudio();
        } else {
            toastr.info('WebAudio API Is Not Available -- Using Fallback');
        }
    });

    function getClips() {
        return Phong.ClipBoard.clips;
    }

    function SoundBoardModel(dataFetcher) {
        var self = this;

        self.clips  = ko.observableArray();

        self.mapData = function (data) {
            $.map(data, function(i, n){
                self.clips.push(new Clip(i));
            });
        };

        /* ---- Events ---- */
        // Play Clip Clicked
        self.playClip = function (model, event) {
            if (jPhong.deviceInfo.supportsAudioContext()) {
                // Use WebAudio API for playback
                if (!model.isPlaying()) {
                    // play
                    self.playClipWithWebAudio(model);
                    model.isPlaying(true);
                } else {
                    // pause
                    if (model.clipNode !== null) {
                        model.clipNode.stop(0);
                        model.clipNode.disconnect();
                        model.clipNode = 0;
                        model.isPlaying(false);
                    }
                }
            }  else {
                // Fall back to using the audio tags (Works for Android)
                var clip = $('audio', $(event.target).parents('div').get(0)).get(0);

                if (clip.paused) {
                    clip.currentTime = 0;

                    model.isPlaying(true);
                    clip.play();
                } else {
                    model.isPlaying(false);
                    clip.pause();
                }
            }
        };

        self.playClipWithWebAudio = function (clipModel) {
            clipModel.clipNode = play(clipModel.buffer);
            clipModel.clipNode.onended = function(e) {
              clipModel.isPlaying(false);
            };
            //var src = clipModel.clipSources[0].source;
            //loadFile(src);
        }

        // Clip Playback Ended
        self.clipEnded = function (model, event) {
            model.isPlaying(false);
        };

        self.loading = function(model, event) {
            //model.isLoading(true);
        };

        self.canPlay = function (model, event) {
            model.isLoading(false);
        };

        // Init Model
        self.mapData(dataFetcher());
    }

    // Instantiate a new clip
    function Clip (data) {
        var self = this;

        this.clipSources = data.clipSources;
        this.defaultImage = data.defaultImage;
        this.hoverImage = data.hoverImage;
        this.clipInfo = data.clipInfo;
        this.playingImage = data.playingImage;
        this.buffer = null;
        this.clipNode = null;
        this.isPlaying = ko.observable(false);
        this.isLoading = ko.observable(true);

        if (jPhong.deviceInfo.supportsAudioContext()){
            var tryLoad = function() {

            };
            // Start loading clip
            loadFile(this.clipSources[0].source, function(buffer){
                self.buffer = buffer;
                self.isLoading(false);
            }, function(error){
                console.log('error');
            });

        }
    }

    /* --- Custom binding handlers --- */
    ko.bindingHandlers.playbackImage = {
        update: function(element, valueAccessor, allBindingsAccessor, viewModel, bindingContext) {
            //console.log('updated');
            var valueUnwrapped = ko.utils.unwrapObservable(valueAccessor());

            if (valueUnwrapped) {
                $(element).attr('src', viewModel.playingImage);
            } else {
                $(element).attr('src', viewModel.defaultImage);
            }
        }
    };

    ko.bindingHandlers.loadingSpinner = {
        update: function(element, valueAccessor, allBindingsAccessor, viewModel, bindingContext) {
            console.log('updated');
            var valueUnwrapped = ko.utils.unwrapObservable(valueAccessor());

            if (valueUnwrapped) {
                $(element).show();
            } else {
                $(element).hide();
            }
        }
    };

    /* --- Web Audio API Methods --- */
    function createAudioContext(){
        if (typeof window.AudioContext !== 'undefined'){
            return new AudioContext();
        }

        if (typeof  window.webkitAudioContext !== 'undefined'){
            return new webkitAudioContext();
        }
    }
    function initAudio() {
        try {
            normailizeWebAPI();
            ctx = createAudioContext();
            mainVol = ctx.createGain();
            mainVol.gain.value = 0.95;
            mainVol.connect(ctx.destination);
        } catch (e) {
            console.log('you need webAudio support');
            toastr.error('You need WebAudio support');
        }
    }

    function normailizeWebAPI() {
        this.AudioContext = this.AudioContext || this.webkitAudioContext;
        this.AudioContext.createGain = this.AudioContext.createGain || this.AudioContext.createGainNode;

        if (typeof this.AudioBufferSourceNode !== 'undefined') {
            this.AudioBufferSourceNode.prototype.start = this.AudioBufferSourceNode.prototype.start || this.AudioBufferSourceNode.prototype.noteOn;
        }
    }

    // !!! Note: change this to accept a callback, run this when the model is binding and in the callback assign the buffer to a member of the model
    function loadFile(url, callback, errorCallBack) {

        var req = new XMLHttpRequest();
        req.open('GET', url, true);
        req.responseType = 'arraybuffer';
        req.onload = function() {

                ctx.decodeAudioData(req.response, function(buffer){
                    callback(buffer);
                }, function(e){
                    if (typeof errorCallBack !== 'undefined'){
                        if (typeof e === 'undefined'){
                          e = new {'message': 'Error decoding the data.'};
                        }

                        errorCallBack(e);
                    }
                });
        };


        req.send();
    }

    function play(buffer){
        var src = ctx.createBufferSource();
        src.buffer = buffer;
        src.playbackRate = 1.0;
        src.connect(mainVol);
        src.start(0);

        return src;
    }

    /* IPhone Sound Activate -- Must be publicly exposed so the UI can call this directly or IO won't allow it */
    function activateAudioForIOS () {
        // create empty buffer
        var buffer = ctx.createBuffer(1, 1, 22050);
        var source = ctx.createBufferSource();
        source.buffer = buffer;

        // connect to output (your speakers)
        source.connect(ctx.destination);

        // play the file
        source.start(0);

        $('#iphoneKludge').hide();
        toastr.info('Sound Activated');
    }

    // Expose methods globally
    var SoundBoard = {
        activateAudioForIOS: activateAudioForIOS,
    };

    window.SoundBoard = SoundBoard;
})();