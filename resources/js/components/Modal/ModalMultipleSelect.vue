<template>
  <b-modal
    ref="multiple-select-modal"
    v-model="show"
    static
    hide-footer
    no-close-on-backdrop
  >
    <template #modal-header>
      <h6 class="modal-title font-weight-bold">
        {{ $t('SEARCH_JOB_LIST.TITLE_SELECT_OCCUPATION') }}
      </h6>

      <button type="button" class="close" @click="handleCloseModal()">
        <span aria-hidden="true">&times;</span>
      </button>
    </template>

    <div class="content-modal">
      <div class="w-100 content-modal-options">
        <div class="collapse-parents">
          <div
            v-for="(item, index) in options"
            :key="index"
            class="collapse-item btn"
            :class="{
              'collapse-item--active':
                childrentSelected[item.id] &&
                childrentSelected[item.id].length > 0,
              'collapse-item--selected': parentSelected.id === item.id,
            }"
            @click="handleSelectParent(item)"
          >
            <span>{{ item.name_ja }}</span>

            <img :src="require('@/assets/images/login/chervon-right-white.png')" alt="collapse" style="height: 18px; width: 8px">
          </div>
        </div>

        <div class="content-collapse-options">
          <div class="head-collapse-parents-title" :class="{ 'd-none': !parentSelected.id }">
            <b-form-checkbox
              id="checkbox-1"
              v-mode="parentCheck[`${parentSelected.id}`]"
              :checked="parentCheck[`${parentSelected.id}`]"
              @change="handleSelectAllChild"
            >
              <span>{{ parentSelected.name_ja ? parentSelected.name_ja : '' }}</span>
            </b-form-checkbox>
          </div>

          <div class="list-check-box">
            <b-form-checkbox-group
              id="childOption"
              v-model="childrentSelected[`${parentSelected.id}`]"
              stacked
              name="childOption"
              :options="childOptions"
              class="checkbox-layout"
              @change="changeChildCheckbox"
            />
          </div>
        </div>
      </div>

      <div class="select-job-btns">
        <div class="btn btn-reflect-the-content" @click="handleReflectContent()">
          <span>{{ $t('SEARCH_JOB_LIST.REFLECT_CONTENT') }}</span>
        </div>

        <div class="btn btn-clear-settings" @click="handleClearSettingsModal()">
          <span>{{ $t('SEARCH_JOB_LIST.CLEAR_SETTINGS') }}</span>
        </div>
      </div>
    </div>
  </b-modal>
</template>

<script>
export default {
  name: 'ModalMultipleSelect',
  components: {},
  props: {
    options: {
      default: () => [],
      type: Array,
    },
    showModal: {
      type: Boolean,
      require: false,
      default: function() {
        return false;
      },
    },
  },
  data() {
    return {
      show: false,
      parentSelected: {},
      parentCheck: {},
      childrentSelected: {},
      childOptions: [],
    };
  },
  watch: {
    showModal: {
      handler(newValue) {
        if (newValue) {
          this.show = true;
        } else {
          this.show = false;
        }
      },
    },
    show() {
      this.$bus.emit('modalSpecifyJobExpShowStatusChanged', this.show);
    },
  },
  created() {
    this.$bus.on('removeSelected', (id) => {
      delete this.childrentSelected[id];
      delete this.parentCheck[id];

      this.parentSelected = {};
      this.childOptions = [];
    });

    this.$bus.on('showModalSelect', (status) => {
      this.show = status;
    });

    this.$bus.on('handleClearSettingsModal', () => {
      this.handleClearSettingsModal();
    });
  },
  destroyed() {
    this.$bus.off('removeSelected');
    this.$bus.off('showModalSelect');
    this.$bus.off('handleClearSettingsModal');
  },
  methods: {
    handleCloseModal() {
      this.show = false;
    },
    handleSelectParent(item) {
      this.parentSelected = item;

      const childList = [];

      item.childOptions.length > 0 && item.childOptions.map((item) => {
        childList.push({
          text: item.name_ja,
          value: item.id,
        });
      });

      this.childOptions = childList;
    },
    changeChildCheckbox() {
      if (this.parentSelected.childOptions.length === this.childrentSelected[this.parentSelected.id].length) {
        this.parentCheck = {
          ...this.parentCheck,
          [`${this.parentSelected.id}`]: true,
        };
      } else {
        this.parentCheck = {
          ...this.parentCheck,
          [`${this.parentSelected.id}`]: false,
        };
      }
    },
    handleSelectAllChild() {
      const checked = !this.parentCheck[`${this.parentSelected.id}`];

      this.parentCheck = {
        ...this.parentCheck,
        [`${this.parentSelected.id}`]: checked,
      };

      if (checked) {
        const childChecked = [];

        this.parentSelected.childOptions.length > 0 && this.parentSelected.childOptions.map((item) => {
          childChecked.push(item.id);
        });

        this.childrentSelected = {
          ...this.childrentSelected,
          [this.parentSelected.id]: childChecked,
        };
      } else {
        this.childrentSelected = {
          ...this.childrentSelected,
          [this.parentSelected.id]: [],
        };
      }
    },
    handleReflectContent() {
      const listSelectedKeys = Object.keys(this.childrentSelected);

      let newListChildrentSelected = {};

      listSelectedKeys.length > 0 && listSelectedKeys.map((id) => {
        if (this.childrentSelected[id].length > 0) {
          newListChildrentSelected = {
            ...newListChildrentSelected,
            [id]: this.childrentSelected[id],
          };
        }
      });

      this.childrentSelected = newListChildrentSelected;

      this.$bus.emit('dataModalMultiple', this.childrentSelected);

      this.show = false;
    },
    handleClearSettingsModal() {
      this.parentSelected = {};
      this.parentCheck = {};

      this.childrentSelected = {};
      this.childOptions = [];

      this.$bus.emit('dataModalMultiple', this.childrentSelected);
    },
  },
};
</script>

<style lang="scss" scoped>

@import '@/scss/_variables.scss';

::v-deep .modal-content {
  min-width: 782px;
  min-height: 512px;
  transform: translate(-18%, 25%);
}

::v-deep .modal-body {
  align-items: stretch;
}

.content-modal {
  width: 100%;
  height: 100%;
  gap: 1.25rem;
  display: flex;
  align-items: stretch;
  flex-direction: column;
  justify-content: center;
}

.content-modal-options {
  width: 100%;
  gap: 1.5rem;
  display: flex;
  align-items: stretch;
  justify-content: center;
}

.collapse-parents {
  width: 40%;
  height: 315px;
  overflow-y: auto;

  &::-webkit-scrollbar {
    width: 3px !important;
  }
}

.collapse-item {
  display: flex;
  border-top: none;
  padding: 1.076rem;
  position: relative;
  background: #ffffff;
  align-items: center;
  border: 1px solid #c6c6c6;
  border-radius: 0 !important;
  justify-content: flex-start;

  &:first-child {
    border-top: 1px solid #c6c6c6;
    border-radius: 5px 5px 0 0 !important;
  }

  & span {
    color: #333333;
    font-size: 14px;
    font-weight: 700;
    line-height: 17px;
  }

  & img:last-child {
    top: 1rem;
    right: 1rem;
    position: absolute;
  }
}
.checked {
  top: 1.1rem;
  height: 17px;
  right: 2.5rem;
  position: absolute;
}
.collapse-item--active {
  color: #333333;
  background: #304cad;

  & span {
    color: #ffffff;
  }
}

.collapse-item--selected {
  color: #333333;
  background: #304cad !important;

  & span {
    color: #ffffff;
  }
}

.content-collapse-options {
  width: 60%;
  height: 315px;
  border-radius: 5px;
  background: #ffffff;
  padding: 0.25rem 0.5rem;
  border: 1px solid #c6c6c6;
}

.head-collapse-parents-title {
  padding: 0.5rem 0.75rem;
  background: #f1f1f1;
  box-shadow: 0px 2px 4px rgba(61, 61, 61, 0.25);

  & span {
    color: #072470;
    font-size: 14px;
    font-weight: 700;
    line-height: 17px;
  }
}

.list-check-box {
  height: 100%;
  padding: 1.25rem 0 0 2.25rem;

  & > div {
    height: 85%;
    overflow-y: auto;
  }
}

.d-none {
  visibility: hidden;
}

.text-blue {
  color: blue;
}
</style>
