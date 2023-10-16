<!-- DistributeMsgCreate -->
<!-- /home/distribute -->
<template>
  <b-overlay
    :show="overlay.show"
    :blur="overlay.blur"
    :rounded="overlay.sm"
    :style="'min-height: 100vh; height: 100%'"
    :variant="overlay.variant"
    :opacity="overlay.opacity"
  >
    <template #overlay>
      <div class="text-center">
        <b-icon icon="arrow-clockwise" font-scale="3" animation="spin" />
        <p style="margin-top: 10px">
          {{ $t('PLEASE_WAIT') }}
        </p>
      </div>
    </template>
    <!--  -->
    <div class="distribute_msg_create">
      <div class="distribute-msg-btn-top">
        <b-button
          dusk="back-home"
          class="btn_back--custom"
          @click="goToBackHomeMsgs()"
        >
          <span>{{ $t('BUTTON_RETURN') }}</span>
        </b-button>
        <b-button
          dusk="create-msg"
          class="btn_save--custom"
          @click="handlePostDistribution()"
        >
          <span>{{ $t('BUTTON.DISTRIBUTE') }}</span>
        </b-button>
      </div>
      <!-- <div>character: {{ character }}</div><br> -->
      <div class="distribute-msg-frame">
        <div class="distribute-msg-frame__title-input">
          <!-- タイトル title -->
          <div class="title-input-title">
            <label class="mr-2" for="edit-title">{{ $t('HOME_MANAGEMENT.TITLE') }}</label>
            <!-- <b-badge class="badge-required mx-2" variant="light">{{
              $t('REQUIRED')
            }}</b-badge> -->
            <Require />
          </div>

          <div class="input-title">
            <b-form-input
              id="distribute-msg-input-title"
              v-model="formData.title"
              dusk="title"
              type="text"
              max-length="50"
              placeholder=""
              :class="error.title === false ? 'is-invalid' : ''"
              :formatter="format50characters"
              enabled
              aria-describedby="title"
              :name="'title'"
              @input="handleChangeForm($event, 'title')"
            />
            <span
              class="count-character"
            >{{ character.title ? character.title : 0 }}/50</span>
          </div>

          <b-form-invalid-feedback id="title" :state="error.title">
            {{ $t('VALIDATE.REQUIRED_TEXT') }}
          </b-form-invalid-feedback>
        </div>

        <!-- Thêm Text và ảnh -->
        <div class="distribute-msg-frame__inputs-datas">
          <!-- title -->
          <div class="distribute-msg-frame__title-input">
            <div class="title-input-title">
              <label for="upload-msg-picture" class="distribute-title mr-2">
                {{ $t('HOME_MANAGEMENT.TEXT') }}
              </label>
              <!-- <b-badge class="badge-required mx-2" variant="light">{{
                $t('REQUIRED')
              }}</b-badge> -->
              <Require />
            </div>
          </div>
          <!-- area -->
          <div class="distribute-msg-frame__inputs">
            <div class="input-datas-frame">
              <div class="w-100">
                <b-form-textarea
                  id="distribute-msg-input-text"
                  v-model="formData.text"
                  dusk="description"
                  enabled
                  rows="12"
                  max-rows="28"
                  placeholder=""
                  max-lengh="1000"
                  aria-describedby="text"
                  :name="'text'"
                  :class="error.text === false ? 'is-invalid' : ''"
                  class="textarea-custom"
                  :formatter="format1000characters"
                  @input="handleChangeForm($event, 'text')"
                />
                <b-form-invalid-feedback id="text" :state="error.title">
                  {{ $t('VALIDATE.REQUIRED_TEXT') }}
                </b-form-invalid-feedback>
              </div>

              <div class="input-data-btns">
                <div>
                  <div>
                    <label for="file-input" class="custom-file-upload">
                      <!-- <i class="fa fa-cloud-upload" /> Chọn tệp -->
                      <img
                        :src="
                          require('@/assets/images/login/picture-upload.png')
                        "
                        alt="img"
                        style="width: 40px"
                      >
                    </label>

                    <input
                      id="file-input"
                      ref="eventRefFileImg"
                      dusk="file-input"
                      type="file"
                      @input="($event) => handleUploadImg($event)"
                    >
                  </div>

                  <div class="img-preview">
                    <img v-if="imagePreview" :src="imagePreview" alt="">
                  </div>
                </div>

                <div class="count-character">
                  {{ character.text ? character.text : 0 }}/1000
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--  -->
    <b-modal
      id="modal-confirm-to-leave"
      v-model="stateModalConfirmToLeave"
      hide-head
      hide-footer
      class="modal-confirm-to-leave-custom"
    >
      <div class="confirm-to-leave">
        <div
          class="confirm-to-leave__title w-100 d-flex justify-center align-center text-center fw-400"
        >
          <span>{{ $t('HOME_MANAGEMENT.CANCELLATION_CONFIRMATION') }}</span>
        </div>

        <div
          class="input-data-btns w-100 d-flex justify-center align-center pt-4"
        >
          <div class="mr-2" @click="cancleModalConfirmToLeave">
            <Button
              id="btn-go-to-back-home-"
              :text="$t('BUTTON.CANCEL')"
              :border="'1px solid #8F8F8F'"
              :border-radius="'50px'"
              :back-ground="'#8F8F8F'"
              :height="'30px'"
              :padding="'0 1.25rem'"
              :color="'#ffffff'"
              :font-size="'16px'"
              :font-weight="'400'"
              :display="'flex'"
              :justify-content="'center'"
              :align-items="'center'"
            />
          </div>

          <div @click="confirmToLeave">
            <Button
              id="btn-go-to-back-home-"
              :text="'Ok'"
              :border="'1px solid #F9BE00'"
              :border-radius="'50px'"
              :back-ground="'#F9BE00'"
              :height="'30px'"
              :padding="'0 1.25rem'"
              :color="'#ffffff'"
              :font-size="'16px'"
              :font-weight="'400'"
              :display="'flex'"
              :justify-content="'center'"
              :align-items="'center'"
            />
          </div>
        </div>
      </div>
    </b-modal>
    <!--  -->
  </b-overlay>
</template>

<script>
import { addDistribution } from '@/api/user.js';
import { MakeToast } from '@/utils/toastMessage';
import { uploadFile } from '@/api/uploadFile';
import Button from '@/components/Button';
import Require from '@/components/Require/Require.vue';

export default {
  name: 'DistributeMsg',
  components: {
    Button,
    Require,
  },

  data() {
    return {
      overlay: {
        show: false,
        variant: 'light',
        opacity: 0,
        blur: '1rem',
        rounded: 'sm',
      },
      stateModalConfirmToLeave: false,

      formData: {
        title: '',
        text: '',
        image_id: null,
      },

      character: {
        title: null,
        text: null,
      },

      error: {
        title: true,
        text: true,
        image_id: true,
        img_text: '',
      },
      editting: false,
      imagePreview: null,
      // imgUploading: false,
      postImgSuccess: false,
      eventFileImgPev: {},
      eventFileImg: {},

      distribute_img_data:
        'https://upload.wikimedia.org/wikipedia/commons/thumb/4/43/Red_flag.svg/1280px-Red_flag.svg.png',
    };
  },

  computed: {},

  watch: {
    formData(newValue) {
      this.charCount = newValue.length;
    },
  },

  created() {
    // this.checkEditing();
  },

  methods: {
    format50characters(e) {
      return String(e).substring(0, 50);
    },
    format1000characters(e) {
      return String(e).substring(0, 1000);
    },

    checkEditing() {
      if (
        this.formData.title !== '' ||
        this.formData.text !== '' ||
        this.eventFileImg.target ||
        this.formData.image_id !== null
      ) {
        this.editting = true;
        return true;
      } else {
        return false;
      }
    },

    async goToBackHomeMsgs() {
      // await this.checkEditing();
      if (this.checkEditing()) {
        this.stateModalConfirmToLeave = true;
      } else {
        this.stateModalConfirmToLeave = false;
        this.$router.push('/home');
      }
    },

    cancleModalConfirmToLeave() {
      this.stateModalConfirmToLeave = false;
    },

    confirmToLeave() {
      this.formData.title = '';
      this.formData.text = '';
      this.formData.image_id = null;
      this.eventFileImg = {}; //
      this.eventFileImgPev = {}; //
      this.$refs.eventRefFileImg.value = null; // reset event of img
      this.$refs.imagePreview.value = null; // reset event of img

      this.stateModalConfirmToLeave = false;
      this.$router.push('/home');
    },

    handleChangeForm(event, field) {
      const newValue = event;
      this.checkEditing();
      switch (field) {
        // 1
        case 'title':
          this.error.title = null;
          if (newValue) {
            this.error.title = true;
            this.character.title = parseInt(this.formData.title.length);
          } else {
            this.error.title = false;
            this.character.title = 0;
          }
          break;
        // 2
        case 'text':
          this.error.text = null;
          if (newValue) {
            this.error.text = true;
            this.character.text = parseInt(this.formData.text.length);
          } else {
            this.error.text = false;
            this.character.text = 0;
          }
          break;
        //
      }
    },

    checkvalidate() {
      if (this.formData.title === '') {
        this.error.title = false;
      }
      if (this.formData.text === '') {
        this.error.text = false;
      }
      if (
        this.formData.title !== '' &&
        this.formData.text !== ''
        // || this.formData.image_id !== '' // wait
      ) {
        return true;
      }
    },

    handlePostDistribution() {
      this.postDistribution();
    },

    async postDistribution() {
      this.checkvalidate();

      if (this.checkvalidate()) {
        const params = {
          title: this.formData.title,
          text: this.formData.text,
          image_id: this.formData.image_id,
        };
        try {
          this.overlay.show = true;
          const response = await addDistribution(params);
          const { code, message } = response['data'];
          if (code === 200) {
            this.overlay.show = false;
            MakeToast({
              variant: 'success',
              title: this.$t('SUCCESS'),
              content:
                message ||
                this.$t('HOME_MANAGEMENT.CREATE_DISTRIBUILT_SUCCESSFULLY'),
            });
            if (this.postImgSuccess === true) {
              this.$refs.eventRefFileImg.value = null; // reset event of img
            }
            this.$router.push('/home'); // !!!
            //
          } else {
            this.overlay.show = false;
            MakeToast({
              variant: 'warning',
              title: 'WARNING',
              content: message || '',
            });
          }
        } catch (error) {
          this.overlay.show = false;
          console.log(error);
        }
      }
    },

    async handleUploadImg(event) {
      if (event.target.value) {
        // this.eventRefFileImg.value = null; // reset event of img
        // this.eventFileImg = event;
        if ((await this.validateUploadImg(event)) === true) {
          this.postImg(event);
        }
      }
    },

    async validateUploadImg(event) {
      // 3MB max, jpeg, jpg, gif, png

      if (event) {
        const rowFileData = event.target.files[0];

        const fileSizeBytes = rowFileData.size;
        const fileSizeMb = fileSizeBytes * 0.000001; // To Mb

        const formats = ['jpeg', 'jpg', 'gif', 'png'];
        const fileFormat = rowFileData['type'];
        const fileFormatSplice1 = String(`${fileFormat.split('/')[1]}`);

        if (!formats.includes(fileFormatSplice1)) {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: this.$t(
              'HOME_MANAGEMENT.PLEASE_DOWNLOAD_THE_ATTACHMENT_CORRECT_FORMAT'
            ),
          });
        }

        if (fileSizeMb > 3) {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: this.$t(
              'HOME_MANAGEMENT.PLEASE_UPLOAD_IMAGES_NO_LARGER_THAN_MB'
            ),
          });
        }

        if (fileSizeMb <= 3 && formats.includes(fileFormatSplice1)) {
          await this.previewImage(event);
          return true;
        }
      }
    },

    previewImage(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.imagePreview = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    },

    async postImg(event) {
      const rowFileData = event.target.files[0];
      if (!rowFileData) {
        return 0;
      }

      const formDataImage = new FormData();
      formDataImage.append('file', rowFileData);
      try {
        this.overlay.show = true;
        const res = await uploadFile(formDataImage);
        this.postImgSuccess = false;
        const { code, data, message } = res.data;
        if (code === 200) {
          this.overlay.show = false;
          this.formData.image_id = data.id;
          this.postImgSuccess = true;
        } else {
          this.overlay.show = false;
          MakeToast({
            variant: 'warning',
            title: 'WARNING',
            content: message || '',
          });
        }
      } catch (error) {
        this.overlay.show = false;
        console.log(error);
      }
    },
    //
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/Job/job.scss';
@import '@/scss/modules/Home/DistributeMsg.scss';
</style>
