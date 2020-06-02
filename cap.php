<div class="row">
                                        <div class="col-md-4">
                                          
                                                  <label for="captcha">Escriba el texto del Captcha</label><br>
                                                  <img src="/resources/captcha.php" alt="CAPTCHA" class="captcha-image">
                                                      <a href="javascript:void(0);" onclick="refreshcaptcha();"><img src='resources/imgs/img_refresh.png' width='30'></a>
                                                  <br> <br>
                                                  
                                                  <input type="text" id="captcha"  onkeydown="upperCaseF(this)" name="captcha_challenge" pattern="[A-Z]{6}" required class="form-control">
                                          
                                        </div>
                                      </div>