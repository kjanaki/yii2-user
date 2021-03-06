<?php

	namespace abhimanyu\user\controllers;

	use abhimanyu\user\models\AccountLoginForm;
	use Yii;
	use yii\filters\AccessControl;
	use yii\filters\VerbFilter;
	use yii\web\Controller;

	class AuthController extends Controller
	{
		public function behaviors()
		{
			return [
				'access' => [
					'class' => AccessControl::className(),
					'rules' => [
						[
							'allow'   => TRUE,
							'actions' => ['login'],
							'roles'   => ['?']
						],
						[
							'allow'   => TRUE,
							'actions' => ['logout'],
							'roles'   => ['@']
						]
					]
				],
				'verbs'  => [
					'class'   => VerbFilter::className(),
					'actions' => [
						'logout' => ['post']
					]
				]
			];
		}

		/**
		 * Displays the login page.
		 *
		 * @return string|\yii\web\Response
		 */
		public function actionLogin()
		{
			// If the user is logged in, redirect to dashboard
			if (!Yii::$app->user->isGuest)
				return $this->redirect(Yii::$app->user->returnUrl);

			$model = new AccountLoginForm();

			if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->login())
				return $this->redirect(Yii::$app->user->returnUrl);

			return $this->render('login', ['model' => $model, 'canRegister' => Yii::$app->config->get('user.enableRegistration')]);
		}

		/**
		 * Logs the user out and then redirects to the homepage.
		 *
		 * @return \yii\web\Response
		 */
		public function actionLogout()
		{
			Yii::$app->user->logout();

			return $this->goHome();
		}
	}