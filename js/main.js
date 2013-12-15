
// Array Remove - By John Resig (MIT Licensed)
Array.prototype.remove = function(from, to) {
    var rest = this.slice((to || from) + 1 || this.length);
    this.length = from < 0 ? this.length + from : from;
    return this.push.apply(this, rest);
};


// Traverse the tree
var gaeJoomla = {

  bucket: '',
  stop: false,
  status: $('#status'),
  progressWrapper: $('#progressWrapper'),
  progress: $('#progress'),
  dataSize: 0,
  data: [],

  // Set data
  setData: function(data)
  {
    this.data = data;
    this.dataSize = data.length;
  },

  // Update progress
  updateProgress: function(i)
  {
    this.data.remove(i);
    var value = 100 - Math.round(this.data.length / this.dataSize * 100);
    this.progress.html(value + ' %')
  },

  // Start Pushing GitHub Objects
  initialize: function(data)
  {
    if (typeof data != 'undefined')
    {
      this.bucket = $('#bucket').val();
      this.stop = false;
      this.setData(data);
      this.progressWrapper.show();
    }

    // Check if tasks needs stopping
    if (this.stop)
      return;

    // Get key of first item
    var key = this.firstKey(),
        $this = this,
        params = this.data[key];

    $.post('/init.php', params, function(res)
    {
        if (typeof res.Status == 'undefined')
        {
            $this.printStatus('Unrecognized status. See console');
        }
        else if (res.Status == 'ERROR')
        {
            $this.printStatus(res.Msg);
        }
        else
        {
            $this.updateProgress(key);
            $this.initialize();
        }
    });
  },

  firstKey: function() {
    for (i in this.data)
        return i;
   return null;
  },

  stopIt: function()
  {
    this.stop = true;
  },

  printStatus: function(msg, type)
  {
    var $div = $('<div>').addClass('alert alert-danger').text(msg);
    $('<button>').addClass('close').attr('data-dismiss', 'alert').html('&times;').appendTo($div);

    this.status.append($div)
  }
};
