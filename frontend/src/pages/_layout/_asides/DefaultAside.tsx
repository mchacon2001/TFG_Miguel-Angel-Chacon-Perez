import React, { useContext, useEffect } from "react";
import Brand from "../../../layout/Brand/Brand";
import Navigation, { NavigationLine } from "../../../layout/Navigation/Navigation";
import User from "../../../layout/User/User";
import { superAdminMenu, adminMenu } from "../../../menu";
import ThemeContext from "../../../contexts/themeContext";
import Aside, { AsideBody, AsideFoot, AsideHead } from "../../../layout/Aside/Aside";
import { PrivilegeContext } from "../../../components/priviledge/PriviledgeProvider";

/**
 * Filter menu based on user permissions
 */
const filterMenu = (menu: any, userCan: Function) => {
  return Object.keys(menu).reduce((obj: any, key: string) => {
    const menuItem = menu[key];
    let clonedMenuItem = { ...menuItem };

    if (menuItem.permissions_required !== undefined) {
      const { action, entity } = menuItem.permissions_required;
      if (!userCan(action, entity)) {
        return obj;
      }
    }

    if (menuItem.subMenu !== undefined && menuItem.subMenu !== null) {
      const filteredSubMenu = filterMenu(menuItem.subMenu, userCan);
      if (Object.keys(filteredSubMenu).length === 0) {
        return obj;
      }
      clonedMenuItem.subMenu = filteredSubMenu;
    }

    obj[key] = clonedMenuItem;
    return obj;
  }, {});
};

const DefaultAside = () => {
  const { asideStatus, setAsideStatus } = useContext(ThemeContext);
  const { userCan, permissions } = useContext(PrivilegeContext);

  // Determinar si el usuario es administrador
  const isAdmin = userCan("admin_routines", "routines");
  const isAdminDiets = userCan("admin_diets", "diets");

  const [localAdminMenu, setLocalAdminMenu] = React.useState<any>({});
  const [showMenus, setShowMenus] = React.useState({
    routines: false,
    userRoutines: false,
    diets: false,
    userDiets: false,
    exercises: false,
    educativeResources: false,
    users: false,
    roles: false,
    exerciseCategories: false,
    routineCategories: false,
    food: false,
  });

  useEffect(() => {
    setLocalAdminMenu(adminMenu);

    setShowMenus({
      routines: isAdmin,
      userRoutines: !isAdmin && userCan("list", "routines"),
      diets: isAdmin,
      userDiets: !isAdmin && userCan("list", "diets"),
      exercises: userCan("list", "exercises"),
      educativeResources: userCan("list", "educative_resources"),
      users: userCan("list", "user") && userCan("admin_user", "user"),
      roles: userCan("list", "roles") && userCan("admin_roles", "roles"),
      exerciseCategories: userCan("list", "exercises") && userCan("admin_exercises", "exercises"),
      routineCategories: userCan("list", "routines") && userCan("admin_routines", "routines"),
      food: userCan("list", "food") && userCan("admin_food", "food"),
    });
  }, [permissions, userCan, isAdmin]);

  return (
    <Aside>
      <AsideHead>
        <Brand asideStatus={asideStatus} setAsideStatus={setAsideStatus} />
      </AsideHead>
      <AsideBody>
        {/* Solo uno de estos dos se mostrará según el rol */}
        {(showMenus.routines || showMenus.userRoutines) && (
          <>
            <NavigationLine />
            <div>
              {showMenus.routines && (
                <Navigation menu={{ routines: adminMenu.routines }} id="aside-routines" />
              )}
              {showMenus.userRoutines && (
                <Navigation menu={{ userRoutines: adminMenu.userHasRoutines }} id="aside-user-routines" />
              )}
            </div>
          </>
        )}
        {/* Dietas: solo uno de estos dos se mostrará según el rol */}
        {(showMenus.diets || showMenus.userDiets) && (
          <>
            <NavigationLine />
            <div>
              {showMenus.diets && (
                <Navigation menu={{ diets: adminMenu.diets }} id="aside-diets" />
              )}
              {showMenus.userDiets && (
                <Navigation menu={{ userDiets: adminMenu.userHasDiets }} id="aside-user-diets" />
              )}
            </div>
          </>
        )}
        {/* El resto de menús siguen igual */}
        {(showMenus.exercises || showMenus.educativeResources) && (
          <>
            <NavigationLine />
            <div>
              {showMenus.exercises && (
                <Navigation menu={{ exercises: adminMenu.exercises }} id="aside-exercises" />
              )}
              {showMenus.educativeResources && (
                <Navigation menu={{ educativeResources: adminMenu.educativeResources }} id="aside-educative-resources" />
              )}
            </div>
          </>
        )}
        {(showMenus.users || showMenus.roles || showMenus.exerciseCategories || showMenus.routineCategories || showMenus.food) && (
          <>
            <NavigationLine />
            <div>
              {showMenus.users && (
                <Navigation menu={{ users: adminMenu.users }} id="aside-users" />
              )}
              {showMenus.roles && (
                <Navigation menu={{ roles: superAdminMenu.roles }} id="aside-roles" />
              )}
              {showMenus.exerciseCategories && (
                <Navigation menu={{ exerciseCategories: superAdminMenu.exerciseCategories }} id="aside-exercise-categories" />
              )}
              {showMenus.routineCategories && (
                <Navigation menu={{ routineCategories: superAdminMenu.routineCategories }} id="aside-routine-categories" />
              )}
              {showMenus.food && (
                <Navigation menu={{ food: superAdminMenu.food }} id="aside-food" />
              )}
            </div>
          </>
        )}
      </AsideBody>
      <AsideFoot>
        <User />
      </AsideFoot>
    </Aside>
  );
};

export default DefaultAside;