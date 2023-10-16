import { mount } from '@vue/test-utils';
import DistributeMsgCreate from '@/pages/Home/DistributeMsg/DistributeMsgCreate.vue';

describe('DistributeMsgCreate', () => {
  it('renders correctly', () => {
    const wrapper = mount(DistributeMsgCreate);
    expect(wrapper.exists()).toBe(true);
  });

  it('contains the title input field', () => {
    const wrapper = mount(DistributeMsgCreate);
    expect(wrapper.find('#distribute-msg-input-title').exists()).toBe(true);
  });

  it('contains the text textarea field', () => {
    const wrapper = mount(DistributeMsgCreate);
    expect(wrapper.find('#distribute-msg-input-text').exists()).toBe(true);
  });

  it('emits an event when the back button is clicked', async() => {
    const wrapper = mount(DistributeMsgCreate);
    const backButton = wrapper.find('.distribute-msg-btn-top div:nth-child(1)');
    await backButton.trigger('click');
    expect(wrapper.emitted('goToBackHomeMsgs')).toBeTruthy();
  });

  it('emits an event when the distribute button is clicked', async() => {
    const wrapper = mount(DistributeMsgCreate);
    const distributeButton = wrapper.find('.distribute-msg-btn-top div:nth-child(2)');
    await distributeButton.trigger('click');
    expect(wrapper.emitted('handlePostDistribution')).toBeTruthy();
  });
  it('sets formData.title when title input is changed', async() => {
    const wrapper = mount(DistributeMsgCreate);
    const titleInput = wrapper.find('#distribute-msg-input-title');
    await titleInput.setValue('New Title');
    expect(wrapper.vm.formData.title).toBe('New Title');
  });

  it('sets formData.text when text textarea is changed', async() => {
    const wrapper = mount(DistributeMsgCreate);
    const textTextarea = wrapper.find('#distribute-msg-input-text');
    await textTextarea.setValue('New Text');
    expect(wrapper.vm.formData.text).toBe('New Text');
  });

  it('displays an error message when title input is empty', async() => {
    const wrapper = mount(DistributeMsgCreate);
    const titleInput = wrapper.find('#distribute-msg-input-title');
    await titleInput.setValue('');
    await titleInput.trigger('input');
    const errorMessage = wrapper.find('#title-error-message');
    expect(errorMessage.exists()).toBe(true);
  });

  it('displays an error message when text textarea is empty', async() => {
    const wrapper = mount(DistributeMsgCreate);
    const textTextarea = wrapper.find('#distribute-msg-input-text');
    await textTextarea.setValue('');
    await textTextarea.trigger('input');
    const errorMessage = wrapper.find('#text-error-message');
    expect(errorMessage.exists()).toBe(true);
  });

  it('emits an event when the confirmToLeave button is clicked', async() => {
    const wrapper = mount(DistributeMsgCreate);
    const confirmToLeaveButton = wrapper.find('#confirm-to-leave-button');
    await confirmToLeaveButton.trigger('click');
    expect(wrapper.emitted('confirmToLeave')).toBeTruthy();
  });
  it('sets formData.title when title input is changed', async() => {
    const wrapper = mount(DistributeMsgCreate);
    const titleInput = wrapper.find('#distribute-msg-input-title');
    await titleInput.setValue('New Title');
    expect(wrapper.vm.formData.title).toBe('New Title');
  });

  it('sets formData.text when text textarea is changed', async() => {
    const wrapper = mount(DistributeMsgCreate);
    const textTextarea = wrapper.find('#distribute-msg-input-text');
    await textTextarea.setValue('New Text');
    expect(wrapper.vm.formData.text).toBe('New Text');
  });

  it('displays an error message when title input is empty', async() => {
    const wrapper = mount(DistributeMsgCreate);
    const titleInput = wrapper.find('#distribute-msg-input-title');
    await titleInput.setValue('');
    await titleInput.trigger('input');
    const errorMessage = wrapper.find('#title-error-message');
    expect(errorMessage.exists()).toBe(true);
  });

  it('displays an error message when text textarea is empty', async() => {
    const wrapper = mount(DistributeMsgCreate);
    const textTextarea = wrapper.find('#distribute-msg-input-text');
    await textTextarea.setValue('');
    await textTextarea.trigger('input');
    const errorMessage = wrapper.find('#text-error-message');
    expect(errorMessage.exists()).toBe(true);
  });

  it('emits an event when the confirmToLeave button is clicked', async() => {
    const wrapper = mount(DistributeMsgCreate);
    const confirmToLeaveButton = wrapper.find('#confirm-to-leave-button');
    await confirmToLeaveButton.trigger('click');
    expect(wrapper.emitted('confirmToLeave')).toBeTruthy();
  });
  it('displays the correct title label', () => {
    const wrapper = mount(DistributeMsgCreate);
    const titleLabel = wrapper.find('.title-input-title label');
    expect(titleLabel.text()).toBe(wrapper.vm.$t('HOME_MANAGEMENT.TITLE'));
  });

  it('displays the correct text label', () => {
    const wrapper = mount(DistributeMsgCreate);
    const textLabel = wrapper.find('.distribute-msg-frame__title-input .distribute-title');
    expect(textLabel.text()).toBe(wrapper.vm.$t('HOME_MANAGEMENT.TEXT'));
  });

  it('emits an event with correct data when distributing a message', async() => {
    const wrapper = mount(DistributeMsgCreate);
    wrapper.setData({
      formData: {
        title: 'Test Title',
        text: 'Test Text',
        image_id: 123,
      },
    });

    const distributeButton = wrapper.find('.distribute-msg-btn-top div:nth-child(2)');
    await distributeButton.trigger('click');

    expect(wrapper.emitted('handlePostDistribution')).toBeTruthy();
    expect(wrapper.emitted('handlePostDistribution')[0][0]).toEqual({
      title: 'Test Title',
      text: 'Test Text',
      image_id: 123,
    });
  });

  it('displays the correct overlay content when distributing a message', async() => {
    const wrapper = mount(DistributeMsgCreate);
    wrapper.setData({ overlay: { show: true }});

    await wrapper.vm.$nextTick();

    const overlayContent = wrapper.find('.b-overlay .text-center p');
    expect(overlayContent.text()).toBe(wrapper.vm.$t('PLEASE_WAIT'));
  });

  it('updates character count when title input value changes', async() => {
    const wrapper = mount(DistributeMsgCreate);
    const titleInput = wrapper.find('#distribute-msg-input-title');
    await titleInput.setValue('New Title');
    const characterCount = wrapper.find('.count-character').text();
    expect(characterCount).toBe('9/50');
  });
});

