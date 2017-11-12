<template>
    <div id="app">
        <header>
            <img class="logo" src="img/logo.png" alt="logo" />
        </header>
        <section class="host-cards">
            <div v-for="(host, index) in hosts" :key="host.voicerID" class="card">
                <div @click="showFloat(index)" class="host-image" :style="{ backgroundImage: 'url(../img/profile_' + host.voicerID + '.jpg)' }">
                    <div class="host-detail">点击查看详情</div>
                </div>
                <div class="host-info">
                    <span class="host-name">{{ host.name }}</span>
                    <span @click="vote($event, index, false)" class="host-vote button" :class="{ 'voted': voted, 'selected': host.selected }">{{ voted ? host.voteCount + '票' : '投票' }}</span>
                </div>
            </div>
        </section>
        <transition name="fade">
            <div @click="closeFloat" v-if="floatShow" class="float">
                <div class="card">
                    <div class="card-image" :style="{ backgroundImage: 'url(../img/profile_' + introduction.voicerID + '.jpg)' }"></div>
                    <div class="card-info">
                        <div class="card-text">
                            <p>{{ introduction.name }}</p>
                            <p>{{ introduction.grade + '级' }}</p>
                            <p>{{ introduction.college }}</p>
                            <p>{{ introduction.introduction }}</p>
                        </div>
                        <div @click="vote($event, introduction.index, true)" class="card-vote button" :class="{ 'voted': voted, 'selected': introduction.selected }">{{ voted ? introduction.voteCount + '票' : '投票' }}</div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
const apis = {
    getVoicersList: '/2017_voicers_vote/api/vote/getVoicersList/',
    getVoteStatus: '/2017_voicers_vote/api/user/getVoteStatus/',
    postVote: '/2017_voicers_vote/api/vote/loveUp/'
};

const personList = [
  {
    name: "廖立胜",
    grade: 2017,
    college: "马克思主义学院",
    introduction: "让我，再次拿起话筒吧！"
  },
  {
    name: "刘入豪",
    grade: 2015,
    college: "新闻与传播学院",
    introduction: "爱上你，主播；爱上这一段，最美的时光。"
  },
  {
    name: "林倩雯",
    grade: 2014,
    college: "建筑学院",
    introduction: "不忘初心，砥砺前行"
  },
  {
    name: "蒋格洋",
    grade: 2017,
    college: "经济与贸易学院",
    introduction: "你给我听好"
  },
  {
    name: "陈瑞森",
    grade: 2017,
    college: "艺术学院",
    introduction: "现在，会说，胜过会做。"
  },
  {
    name: "崔艺潆",
    grade: 2017,
    college: "经济与贸易学院",
    introduction: "不求轰轰烈烈，但愿掷地有声。"
  },
  {
    name: "刘一达",
    grade: 2016,
    college: "软件学院",
    introduction: "感受程序员的语言魅力"
  },
  {
    name: "黄新杰",
    grade: 2017,
    college: "材料与工程学院",
    introduction: "让别人因为我的存在而感到幸福"
  }
];

const dataChecker = function(data, json) {
  let type = typeof data;
  switch (type) {
    case "string":
      return data;
    case "object":
      if (json) {
        return JSON.stringify(data);
      } else {
        let counter = 0;
        let targetString = "";
        for (let prop in data) {
          if (data.hasOwnProperty(prop)) {
            let propFilter = prop.replace(/&/g, "");
            let dataFilter = data[prop].toString().replace(/&/g, "");
            if (counter === 0) {
              targetString = targetString + propFilter + "=" + dataFilter;
            } else {
              targetString = targetString + "&" + propFilter + "=" + dataFilter;
            }
            counter++;
          }
        }
        return targetString;
      }
    default:
      return data;
  }
};

const get = function get(url, data, json, successHandle, errorHandle, blob) {
  let xmlhttp = new XMLHttpRequest();
  if (xmlhttp != null) {
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState === 4) {
        // 4 = "loaded"
        if (xmlhttp.status === 200) {
          // 200 = "OK"
          if (blob) {
            successHandle(xmlhttp.response);
          } else {
            successHandle(xmlhttp.responseText);
          }
        } else {
          if (blob) {
            errorHandle(xmlhttp.response);
          } else {
            errorHandle(xmlhttp.statusText);
          }
        }
      }
    };
    let targetData = dataChecker(data, json);
    if (json) {
      targetData = "json=" + targetData;
    }
    xmlhttp.open(
      "GET",
      url + (targetData !== "" ? "?" : "") + targetData,
      true
    );
    if (blob) {
      xmlhttp.responseType = "blob";
    }
    xmlhttp.send(null);
  }
};

const post = function post(url, data, json, successHandle, errorHandle, blob) {
  let xmlhttp = new XMLHttpRequest();
  if (xmlhttp != null) {
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState === 4) {
        // 4 = "loaded"
        if (xmlhttp.status === 200) {
          // 200 = "OK"
          if (blob) {
            successHandle(xmlhttp.response);
          } else {
            successHandle(xmlhttp.responseText);
          }
        } else {
          if (blob) {
            errorHandle(xmlhttp.response);
          } else {
            errorHandle(xmlhttp.statusText);
          }
        }
      }
    };
    let targetData = dataChecker(data, json);
    xmlhttp.open("POST", url, true);
    if (json) {
      xmlhttp.setRequestHeader("Content-Type", "application/json");
    } else {
      xmlhttp.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded"
      );
    }
    if (blob) {
      xmlhttp.responseType = "blob";
    }
    xmlhttp.send(targetData);
  }
};

const errorHandle = function() {
  alert("网络好像不太行啊(っ °Д °;)っ请刷新页面");
};

export default {
  el: "#app",
  data: function() {
    return {
      voted: true,
      floatShow: false,
      hosts: [],
      introduction: {
        voicerID: 0,
        name: "",
        grade: 0,
        college: "",
        introduction: "",
        selected: false,
        voteCount: 0,
        index: 0
      }
    };
  },
  methods: {
    closeFloat: function() {
      this.floatShow = false;
    },
    showFloat: function(index) {
      var detail = this.hosts[index];
      var person = personList[detail.voicerID - 1];
      var intro = {
        voicerID: detail.voicerID,
        name: person.name,
        grade: person.grade,
        college: person.college,
        introduction: person.introduction,
        selected: detail.selected,
        voteCount: detail.voteCount,
        index: index
      };
      this.introduction = intro;
      this.floatShow = true;
    },
    vote: function(event, index, isDetail) {
      event.stopPropagation();
      if (this.voted === true) {
        return;
      }
      var that = this;
      post(
        apis.postVote,
        { voicerID: that.hosts[index].voicerID },
        true,
        function(res) {
          var data = JSON.parse(res);
          if (data.status === 0) {
            Vue.set(app.hosts[index], "voteCount", data.data.voteCount);
            Vue.set(app.hosts[index], "selected", true);
            if (isDetail) {
              that.introduction.selected = true;
              that.introduction.voteCount = data.data.voteCount;
            }
            that.voted = true;
          } else {
            alert(data.errorMessage);
          }
        },
        errorHandle,
        false
      );
    }
  },
  mounted: function() {
    var that = this;

    // for development
    // (function() {
    //     post('/api/staging/setOpenID/', { openID: 4096 }, true, function(res) {}, errorHandle, false);
    // })();

    var getList = function(res) {
      var data = JSON.parse(res);
      if (data.status === -1) {
        window.location.href = data.redirect;
      } else {
        var list = [];
        for (var i = 0; i < data.data.length; i++) {
          var singleData = data.data[i];
          singleData.selected = false;
          list.push(singleData);
        }
        that.hosts = list;
        get(apis.getVoteStatus, null, false, getStatus, errorHandle, false);
      }
    };
    var getStatus = function(res) {
      var data = JSON.parse(res);
      if (data.status === 0) {
        that.voted = data.isVote;
        if (that.voted === true) {
          var i;
          for (i = 0; i < that.hosts.length; i++) {
            if (that.hosts[i].voicerID == data.voicerID) {
              break;
            }
          }
          Vue.set(app.hosts[i], "selected", true);
        }
      } else {
        alert(data.errorMessage);
      }
    };
    get(apis.getVoicersList, null, false, getList, errorHandle, false);
  }
};
</script>

<style>
</style>
