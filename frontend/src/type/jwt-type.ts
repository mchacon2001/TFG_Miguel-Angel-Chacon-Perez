export type JWTDecoded = {
  iat?:           number;
  exp?:           number;
  id?:            string;
  email?:         string;
  name?:          string;
  telephone?:     string;
  address?:       null;
  createdAt?:     Date;
  birthdayDate?:  null;
  active?:        boolean;
  roles?:         string[];
  permissions?:   Permissions;
  imgProfile?:    null;
}

export type Permissions = {
  [key: string]:     string[];
}