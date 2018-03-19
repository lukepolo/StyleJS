import Vue from 'vue'
Vue.mixin({
  methods: {
      updateSync(target, name) {
          let value = target.value;
          name = name ? name : target.name;
          switch(target.type) {
              case 'checkbox':
                  let options = document.querySelectorAll(`input[type="checkbox"][name="${ name }"]`);

                  if(options.length === 1) {
                      if(target.value === 'on') {
                          value = target.checked;
                      }
                  } else {
                      value = [];

                      Array.from(document.querySelectorAll(`input[type="checkbox"][name="${ name }"]`)).forEach((element : HTMLInputElement) => {
                          if(element.checked) {
                              value.push(element.value);
                          }
                      });
                  }
                  break;
              case 'select-multiple':
                  value = Array.from(target.selectedOptions).map((option : { value : any })=> {
                      return option.value;
                  });
                  break;
          }

          this.$emit(`update:${name.toCamelCase()}`, value);
      }
  },
});
