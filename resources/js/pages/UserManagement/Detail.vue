<template>
  <div class="display-user-management-detail">
    <b-row cols="1" cols-sm="2" cols-md="2" cols-lg="2" cols-xl="2">
      <b-col>
        <!-- Button back -->
        <div class="btn-return" :disabled="overlay.show">
          <i class="fad fa-backward" @click="handleBackList()" />
          <span @click="handleBackList()">{{ $t('BUTTON_RETURN') }}</span>
        </div>
      </b-col>

      <b-col>
        <div class="display-control">
          <b-button class="btn-edit" @click="handleGoToEdit()">
            <i class="fas fa-edit" />
            <span>{{ $t('BUTTON_EDIT') }}</span>
          </b-button>

          <b-button class="btn-delete" @click="showModal = !showModal">
            <i class="fas fa-trash-alt" />
            <span>{{ $t('BUTTON_DELETE') }}</span>
          </b-button>
        </div>
      </b-col>

    </b-row>
    <b-col>
      <!-- Zone Input -->
      <b-row>
        <div class="display-detail-user">
          <b-overlay
            :show="overlay.show"
            :variant="overlay.variant"
            :opacity="overlay.opacity"
            :blur="overlay.blur"
            :rounded="overlay.sm"
          >
            <!-- Template overlay -->
            <template #overlay>
              <div class="text-center">
                <b-icon icon="arrow-clockwise" font-scale="3" animation="spin" />
                <p style="margin-top: 10px;">{{ $t('PLEASE_WAIT') }}</p>
              </div>
            </template>

            <div class="zone-detail-user">

              <!-- Input Name -->
              <div>
                <label for="input-create-user-name">
                  {{ $t('USER_MANAGEMENT_CREATE_NAME_REQUIRED') }}
                </label>

                <b-form-input
                  id="input-create-user-name"
                  v-model="User.name"
                  type="text"
                  :disabled="true"
                />
              </div>

              <!-- Input Email -->
              <div>
                <label for="input-create-user-email">
                  {{ $t('USER_MANAGEMENT_CREATE_EMAIL_REQUIRED') }}
                </label>

                <b-form-input
                  id="input-create-user-email"
                  v-model="User.email"
                  type="text"
                  :disabled="true"
                />
              </div>

              <!-- Select Authority -->
              <div>
                <label for="input-create-user-authority">
                  {{ $t('USER_MANAGEMENT_CREATE_AUTHORITY_REQUIRED') }}
                </label>

                <b-form-select
                  id="input-create-user-authority"
                  v-model="User.authority"
                  :disabled="true"
                >
                  <b-form-select-option :value="null" disabled>{{ $t('PLEASE_SELECT_TEXT') }}</b-form-select-option>
                  <b-form-select-option
                    v-for="(itemAuthority, indexAuthority) in constAuthority.AUTHORITY"
                    :key="indexAuthority + 1"
                    :value="indexAuthority + 1"
                  >
                    {{ $t(itemAuthority) }}
                  </b-form-select-option>
                </b-form-select>
              </div>

            </div>
          </b-overlay>
        </div>
      </b-row>

    </b-col>

    <!-- Modal Confirm -->
    <b-modal id="modal-cf-delete" v-model="showModal" :static="true" header-class="modal-custom-header" content-class="modal-custom-body" footer-class="modal-custom-footer">
      <template #modal-header>
        <span>{{ $t('MODAL_CONFIRM_DELETE') }}</span>
      </template>

      <template #default>
        <span>{{ $t('MODAL_MESSAGE_CONFIRM_DELETE', { name: User.name }) }}</span>
      </template>

      <template #modal-footer>
        <b-button class="modal-btn-cancel" @click="showModal = !showModal">{{ $t('MODAL_BUTTON_CANCEL') }}</b-button>

        <b-button class="modal-btn-delete" @click="handleDelete()">{{ $t('MODAL_BUTTON_DELETE') }}</b-button>
      </template>
    </b-modal>
  </div>
</template>

<script>

// import { getUserManagement, deleteUserManagement } from '@/api/modules/userManagement';
import constAuthority from '@/const/authority';
import constUserManager from '@/const/userManagement';
import { validateNumberMoreThanZero } from '@/utils/validate';
// import { getAuthority } from '@/utils/handleRole';
import { MakeToast } from '@/utils/toastMessage';

export default {
  name: 'UserManagementDetail',
  data() {
    return {
      constAuthority: constAuthority,

      User: {
        id: '',
        name: '',
        email: '',
        authority: null,
      },

      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },

      showModal: false,
    };
  },
  created() {
    this.handleDetail();
  },
  methods: {
    /**
     * Function handle back to User Management List
     */
    handleBackList() {
      this.$router.push({ path: '/usermanagement/list' });
    },

    /**
     * Function handle go to User Management Edit
     */
    handleGoToEdit() {
      this.$router.push({ path: `/usermanagement/edit/${this.User.id}` });
    },

    /**
     * Function handle call api detail
     */
    async handleDetail() {
      this.overlay.show = true;

      const ID_USER = {
        [constUserManager.ID]: this.$route.params.id || 0,
      };

      if (this.handleValidateID(ID_USER[constUserManager.ID])) {
        // const URL = `/user/${ID_USER[constUserManager.ID]}`;

        // await getUserManagement(URL)
        //   .then((res) => {
        //     this.User.id = res.data[constUserManager.ID];
        //     this.User.name = res.data[constUserManager.NAME];
        //     this.User.email = res.data[constUserManager.EMAIL];
        //     this.User.authority = getAuthority(res.data[constUserManager.ROLE][0]['name']);
        //   })
        //   .catch(() => {
        //     MakeToast({
        //       variant: 'danger',
        //       title: this.$t('DANGER'),
        //       content: this.$t('TOAST_HAVE_ERROR'),
        //     });
        //   });
      }

      this.overlay.show = false;
    },

    /**
     * Function handle validate ID User
     */
    handleValidateID(id) {
      if (validateNumberMoreThanZero(id)) {
        return true;
      }

      MakeToast({
        variant: 'danger',
        title: this.$t('DANGER'),
        content: this.$t('TOAST_HAVE_ERROR'),
      });

      return false;
    },

    /**
     * Function handle
     */
    async handleDelete() {
      const ID_USER = {
        [constUserManager.ID]: this.$route.params.id || 0,
      };

      if (this.handleValidateID(ID_USER[constUserManager.ID])) {
        // const URL = `/user/${ID_USER[constUserManager.ID]}`;

        // await deleteUserManagement(URL)
        //   .then(() => {
        //     MakeToast({
        //       variant: 'success',
        //       title: this.$t('SUCCESS'),
        //       content: this.$t('USER_MANAGEMENT_DELETE_SUCCESS', { name: this.User.name }),
        //     });

        //     this.handleBackList();
        //   })
        //   .catch(() => {
        //     MakeToast({
        //       variant: 'danger',
        //       title: this.$t('DANGER'),
        //       content: this.$t('TOAST_HAVE_ERROR'),
        //     });
        //   });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/UserManagement/UserDetail.scss';
</style>
