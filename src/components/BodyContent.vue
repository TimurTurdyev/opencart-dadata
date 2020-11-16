<template>
  <div class="columns">
    <div class="column is-fullwidth block-alert" v-if="notification">
      <div class="notification is-warning" v-html="notification"></div>
    </div>
    <div class="column is-3">
      <div class="card">
        <div class="card-content">
          <p class="menu-label">
            Статус модуля
          </p>
          <div class="page_status">
            <div class="onoffswitch">
              <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch_status"
                     tabindex="0"
                     v-on:change="changeStatus()" :checked="status">
              <label class="onoffswitch-label" for="myonoffswitch_status">
                <span class="onoffswitch-inner"></span>
                <span class="onoffswitch-switch"></span>
              </label>
            </div>
          </div>
          <hr>
          <p class="menu-label">
            Страницы/Route
          </p>
          <ul class="menu-list">
            <li v-for="route in routers"
                :key="route">
              <a v-on:click.prevent="route_active = route"
                 :class="{'is-active': route === route_active}">
                {{ route }}
              </a></li>
          </ul>
          <p class="menu-label">
            Добавить route
          </p>
          <div class="field">
            <div class="control">
              <input class="input" type="text" placeholder="Add route" v-model="route_new">
            </div>
          </div>
          <button class="button is-success is-fullwidth" v-on:click="addRoute">Добавить</button>
        </div>
      </div>
    </div>
    <div class="column is-9" v-if="route_active">
      <div class="card">
        <header class="card-header">
          <p class="card-header-title">
            Настройки для {{ route_active }}
          </p>
          <div class="page_status">
            <div class="onoffswitch">
              <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" tabindex="0"
                     v-model="routeSetting.status" :value="routeSetting.status" :checked="routeSetting.status">
              <label class="onoffswitch-label" for="myonoffswitch">
                <span class="onoffswitch-inner"></span>
                <span class="onoffswitch-switch"></span>
              </label>
            </div>
          </div>
        </header>
        <div class="card-content">
          <div class="content">
            <article class="message is-warning">
              <div class="message-body">
                Не забудьте добавить модуль <strong>Dadata Opencart</strong> на route <em>
                {{ route_active }}</em>, в разделе <a :href="base + 'index.php?route=design/layout' + token">схемы</a>.
                Если
                нужного
                route не нашлось создайте его или добавьте к сеществующему.
                Вызвать можно <code>if('{{ route_active.replace('/', '_') }}' in window) {{ route_active.replace('/', '_') }}();</code>
              </div>
            </article>
            <div class="columns">
              <div class="column is-8 block-js">
                <p class="menu-label">
                  Настройки Javascript
                </p>
                <Codemirror v-model="routeSetting.before" name="before" :options="codemirrorJavascript"
                            @input="onCmCodeChangeJavascriptBefore"></Codemirror>
                <p class="menu-label">
                  Вызов Javascript
                </p>
                <Codemirror v-model="routeSetting.after" name="after" :options="codemirrorJavascript"
                            @input="onCmCodeChangeJavascriptAfter"></Codemirror>
              </div>
              <div class="column is-4 block-css">
                <p class="menu-label">
                  Настройки стилей
                </p>
                <Codemirror v-model="routeSetting.css" name="css" :options="codemirrorCss"
                            @input="onCmCodeChangeCss"></Codemirror>
              </div>
            </div>
          </div>
        </div>
        <footer class="card-footer">
          <a href="#" class="card-footer-item" v-on:click.prevent="saveSettingRoute">Сохранить</a>
          <a href="#" class="card-footer-item" v-on:click.prevent="deleteSettingRoute">Удалить</a>
        </footer>
      </div>
    </div>
  </div>
</template>

<script>
import Codemirror from './Codemirror';

const axios = require('axios').default;
export default {
  name: 'HelloWorld',
  components: {Codemirror},
  props: {
    base: String,
    token: String
  },
  data() {
    return {
      notification: '',
      status: 0,
      route_new: '',
      routers: [],
      routeSetting: {
        before: '',
        after: '',
        css: '',
        status: 0,
      },
      route_active: '',
      codemirrorJavascript: {
        tabSize: 2,
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true,
        line: true,
        foldGutter: true,
        styleSelectedText: true,
        mode: "text/javascript",
        keyMap: "sublime",
        matchBrackets: true,
        showCursorWhenSelecting: true,
        theme: "monokai",
        extraKeys: {Ctrl: "autocomplete"},
        hintOptions: {
          completeSingle: false
        }
      },
      codemirrorCss: {
        tabSize: 2,
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true,
        line: true,
        foldGutter: true,
        styleSelectedText: true,
        mode: "text/css",
        keyMap: "sublime",
        matchBrackets: true,
        showCursorWhenSelecting: true,
        theme: "monokai",
        extraKeys: {Ctrl: "autocomplete"},
        hintOptions: {
          completeSingle: false
        }
      },
    }
  },
  watch: {
    route_active: function (value) {
      this.settingRoute(value)
      this.route_active = value;
    },
    notification: function (value) {
      this.notification = value;
      if (value) {
        setTimeout(() => {
          this.notification = '';
        }, 5000);
      }
    }
  },
  methods: {
    changeStatus() {
      this.status = !this.status
      axios.get(`${this.base}index.php?route=catalog/dadata_opencart/changeStatus&status=${(this.status ? 1 : 0)}${this.token}`)
          .then(response => {
            console.log(response.data)
            if (response.data.notification) {
              this.notification = response.data.notification
            }
          }).catch(error => {
        console.log(error);
      });
    },
    addRoute() {
      if (this.route_new && this.routers.indexOf(this.route_new) === -1) {
        this.routers.push(this.route_new)
        this.route_new = '';
      } else if (this.route_new !== '') {
        this.notification = 'Такой route уже есть на странице'
      }
    },
    onCmCodeChangeJavascriptBefore(newValue) {
      this.routeSetting.before = newValue;
    },
    onCmCodeChangeJavascriptAfter(newValue) {
      this.routeSetting.after = newValue;
    },
    onCmCodeChangeCss(newValue) {
      this.routeSetting.css = newValue;
    },
    changePageSetting(data) {
      if (data.notification) {
        this.notification = data.notification
      }
      if (data.before) {
        this.routeSetting.before = data.before
        this.routeSetting.after = data.after
        this.routeSetting.css = data.css
        this.routeSetting.status = data.status || 0
        this.$children.forEach((vm) => {
          vm.myCodemirror.setValue(data[vm.$el.name]);
        })
      } else {
        this.routeSetting.before = ''
        this.routeSetting.after = ''
        this.routeSetting.css = ''
        this.routeSetting.status = ''
        this.$children.forEach((vm) => {
          vm.myCodemirror.setValue('');
        })
      }
    },
    settingRoute(value) {
      axios.get(`${this.base}index.php?route=catalog/dadata_opencart/settingRoute&setting_name=${value}${this.token}`)
          .then(response => {
            console.log(response.data)
            this.changePageSetting(response.data)
          }).catch(error => {
        console.log(error);
      });
    },
    saveSettingRoute() {
      const data = {
        name: this.route_active,
        before: this.routeSetting.before,
        after: this.routeSetting.after,
        css: this.routeSetting.css,
        status: this.routeSetting.status ? 1 : 0,
      }
      axios.post(`${this.base}index.php?route=catalog/dadata_opencart/saveSettingRoute${this.token}`, data)
          .then(response => {
            console.log(response.data)
            if (response.data.notification) {
              if (response.data.notification) {
                this.notification = response.data.notification
              }
            }
          }).catch(error => {
        console.log(error);
      });
    },
    deleteSettingRoute() {
      axios.get(`${this.base}index.php?route=catalog/dadata_opencart/deleteSettingRoute&setting_name=${this.route_active}${this.token}`)
          .then(response => {
            console.log(response.data)
            this.changePageSetting(response.data)
            this.route_active = '';
            this.routers = response.data.routers
            if (response.data.notification) {
              this.notification = response.data.notification
            }
          }).catch(error => {
        console.log(error);
      });
    }
  },
  mounted() {
    axios.get(`${this.base}index.php?route=catalog/dadata_opencart/getRoutes${this.token}`)
        .then(response => {
          console.log(response.data)
          if (response.data.status) {
            this.status = Number(response.data.status)
          }
          if (response.data.routers) {
            this.routers = response.data.routers
          }
        }).catch(error => {
      console.log(error);
    });
  }
}
</script>

<style>
.block-js .CodeMirror {
  height: 40rem;
}

.block-js .CodeMirror:last-child {
  height: 20rem;
}

.CodeMirror {
  height: calc(100% - 2rem);
}

.page_status {
  padding: .5rem 1rem;
}

.onoffswitch {
  position: relative;
  width: 90px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
}

.onoffswitch-checkbox {
  position: absolute;
  opacity: 0;
  pointer-events: none;
}

.onoffswitch-label {
  display: block;
  overflow: hidden;
  cursor: pointer;
  border: 2px solid #999999;
  border-radius: 7px;
}

.onoffswitch-inner {
  display: block;
  width: 200%;
  margin-left: -100%;
  transition: margin 0.3s ease-in 0s;
}

.onoffswitch-inner:before, .onoffswitch-inner:after {
  display: block;
  float: left;
  width: 50%;
  height: 30px;
  padding: 0;
  line-height: 30px;
  font-size: 14px;
  color: white;
  font-family: Trebuchet, Arial, sans-serif;
  font-weight: bold;
  box-sizing: border-box;
}

.onoffswitch-inner:before {
  content: "ON";
  padding-left: 10px;
  background-color: #48c774;
  color: #FFFFFF;
}

.onoffswitch-inner:after {
  content: "OFF";
  padding-right: 10px;
  background-color: #EEEEEE;
  color: #999999;
  text-align: right;
}

.onoffswitch-switch {
  display: block;
  width: 18px;
  margin: 6px;
  background: #FFFFFF;
  position: absolute;
  top: 0;
  bottom: 0;
  right: 56px;
  border: 2px solid #999999;
  border-radius: 7px;
  transition: all 0.3s ease-in 0s;
}

.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
  margin-left: 0;
}

.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
  right: 0px;
}

.block-alert {
  position: fixed;
  z-index: 999;
  width: 28rem;
  right: 1rem;
}
</style>