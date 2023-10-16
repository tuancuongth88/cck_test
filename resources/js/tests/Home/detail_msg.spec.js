import { shallowMount } from '@vue/test-utils';
import DetailMessage from '@/pages/Home/detailMsg.vue';

describe('DetailMessage.vue', () => {
  let wrapper;

  beforeEach(() => {
    wrapper = shallowMount(DetailMessage, {
      mocks: {
        $store: {
          getters: {
            message_id: 123,
            listUser: [],
          },
        },
      },
    });
  });

  afterEach(() => {
    wrapper.destroy();
  });

  it('renders properly', () => {
    const wrapper = shallowMount(DetailMessage);
    expect(wrapper.exists()).toBe(true);
  });

  it('calls handleNavigateToHomeScreen method when back button is clicked', async() => {
    const wrapper = shallowMount(DetailMessage);
    const handleNavigateToHomeScreen = jest.spyOn(wrapper.vm, 'handleNavigateToHomeScreen');

    const backButton = wrapper.find('.btn-back-to-list');
    await backButton.trigger('click');

    expect(handleNavigateToHomeScreen).toHaveBeenCalled();
  });

  it('calls handleGetComponentData method on mount if message_id is provided', async() => {
    const wrapper = shallowMount(DetailMessage, {
      mocks: {
        $store: {
          getters: {
            message_id: 123,
          },
        },
      },
    });

    const handleGetComponentData = jest.spyOn(wrapper.vm, 'handleGetComponentData');
    await wrapper.vm.$nextTick();

    expect(handleGetComponentData).toHaveBeenCalled();
  });

  it('fetches and sets contentHTML when handleGetDetailInformation is called', async() => {
    const mockDetailNotiResponse = {
      data: {
        code: 200,
        message: 'Success',
        data: {
          data: JSON.stringify({
            contentHTML: '<div>Test Content</div>',
          }),
        },
      },
    };

    jest.spyOn(global, 'fetch').mockResolvedValue({
      json: jest.fn().mockResolvedValue(mockDetailNotiResponse),
    });

    const wrapper = shallowMount(DetailMessage, {
      data() {
        return {
          message_id: 123,
        };
      },
    });

    const handleGetDetailInformation = jest.spyOn(wrapper.vm, 'handleGetDetailInformation');
    await wrapper.vm.handleGetDetailInformation();

    expect(handleGetDetailInformation).toHaveBeenCalled();
    expect(wrapper.vm.contentHTML).toBe('<div>Test Content</div>');
  });

  it('handles errors during handleGetDetailInformation and shows a toast', async() => {
    jest.spyOn(global, 'fetch').mockRejectedValue(new Error('API error'));

    const makeToast = jest.spyOn(wrapper.vm, 'MakeToast');
    await wrapper.vm.handleGetDetailInformation();
    expect(makeToast).toHaveBeenCalledWith({
      variant: 'danger',
      title: wrapper.vm.$t('DANGER'),
      content: 'API error',
    });
  });

  it('handles errors during handleUpdateNotification and shows a toast', async() => {
    jest.spyOn(global, 'fetch').mockRejectedValue(new Error('API error'));

    const makeToast = jest.spyOn(wrapper.vm, 'MakeToast');
    await wrapper.vm.handleUpdateNotification();
    expect(makeToast).toHaveBeenCalledWith({
      variant: 'danger',
      title: wrapper.vm.$t('DANGER'),
      content: 'API error',
    });
  });

  it('navigates to the home screen when handleNavigateToHomeScreen is called', async() => {
    const $router = {
      push: jest.fn(),
    };
    wrapper.vm.$router = $router;

    await wrapper.vm.handleNavigateToHomeScreen();
    expect($router.push).toHaveBeenCalledWith({ path: '/home' });
  });

  it('renders contentHTML using v-html', async() => {
    wrapper.vm.contentHTML = '<div>Test HTML Content</div>';
    await wrapper.vm.$nextTick();

    const renderedContent = wrapper.find('.content-render-wrap');
    expect(renderedContent.exists()).toBe(true);
    expect(renderedContent.html()).toContain('<div>Test HTML Content</div>');
  });

  it('updates notification and shows a success toast on handleUpdateNotification success', async() => {
    const mockResponse = {
      data: {
        code: 200,
        message: 'Notification updated successfully',
      },
    };
    jest.spyOn(global, 'fetch').mockResolvedValue({
      json: jest.fn().mockResolvedValue(mockResponse),
    });

    const makeToast = jest.spyOn(wrapper.vm, 'MakeToast');
    await wrapper.vm.handleUpdateNotification();
    expect(makeToast).toHaveBeenCalledWith({
      variant: 'success',
      title: wrapper.vm.$t('SUCCESS'),
      content: 'Notification updated successfully',
    });
  });

  it('handles API error during handleUpdateNotification', async() => {
    jest.spyOn(global, 'fetch').mockRejectedValue(new Error('API error'));

    const makeToast = jest.spyOn(wrapper.vm, 'MakeToast');
    await wrapper.vm.handleUpdateNotification();
    expect(makeToast).toHaveBeenCalledWith({
      variant: 'danger',
      title: wrapper.vm.$t('DANGER'),
      content: 'API error',
    });
  });

  it('renders a back-to-list button with the correct text', () => {
    const backButton = wrapper.find('.btn-back-to-list');
    expect(backButton.exists()).toBe(true);
    expect(backButton.text()).toBe(wrapper.vm.$t('BUTTON.BACK_TO_LIST'));
  });

  it('navigates to the home screen when the back-to-list button is clicked', async() => {
    const $router = {
      push: jest.fn(),
    };
    wrapper.vm.$router = $router;

    const backButton = wrapper.find('.btn-back-to-list');
    await backButton.trigger('click');
    expect($router.push).toHaveBeenCalledWith({ path: '/home' });
  });
});
