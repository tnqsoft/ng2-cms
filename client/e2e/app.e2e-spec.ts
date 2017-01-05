import { MasterialPage } from './app.po';

describe('masterial App', function() {
  let page: MasterialPage;

  beforeEach(() => {
    page = new MasterialPage();
  });

  it('should display message saying app works', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('app works!');
  });
});
