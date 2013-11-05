(function(){
    var viewModel;

    $(function(){
        viewModel = new SoundBoardModel(getClips);
        ko.applyBindings(viewModel);
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

        // Events
        self.playClip = function (model, event) {
            var clip = $(event.currentTarget).next('audio').get(0);


            if (clip.paused) {
                clip.currentTime = 0;

                model.isPlaying(true);
                clip.play();
            } else {
                model.isPlaying(false);
                clip.pause();
            }
        };

        self.clipEnded = function (model, event) {
            model.isPlaying(false);
        };


        self.loading = function(model, event) {
            model.isLoading(true);
        };

        self.canPlay = function (model, event) {
            model.isLoading(false);
        };

        // Init Model
        self.mapData(dataFetcher());
    }

    function Clip (data) {
        var self = this;

        this.clipSources = data.clipSources;
        this.defaultImage = data.defaultImage;
        this.hoverImage = data.hoverImage;
        this.clipInfo = data.clipInfo;
        this.playingImage = data.playingImage;
        this.isPlaying = ko.observable(false);
        this.isLoading = ko.observable(false);
    }

    // Custom binding handlers
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

    // Custom binding handlers
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

})();