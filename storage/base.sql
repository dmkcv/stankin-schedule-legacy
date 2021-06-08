CREATE TABLE chairs (
  id         INTEGER         NOT NULL,
  short_name VARCHAR( 50 )   DEFAULT NULL,
  full_name  VARCHAR( 255 )  DEFAULT NULL,
  PRIMARY KEY ( id )
);

CREATE TABLE classes (
  id            INTEGER        NOT NULL,
  name          VARCHAR( 50 )  DEFAULT NULL,
  normalized_name          VARCHAR( 50 )  DEFAULT NULL,
  speciality_id INTEGER        DEFAULT NULL,
  semester      INTEGER        DEFAULT NULL,
  PRIMARY KEY ( id )
);

CREATE TABLE loads (
  listid        INTEGER        NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  id            INTEGER        NOT NULL,
  same_time     INTEGER        DEFAULT NULL,
  teacher_id    INTEGER        NOT NULL,
  subject_id    INTEGER        NOT NULL,
  room_id       INTEGER        NOT NULL,
  week_type     VARCHAR( 50 )  NOT NULL,
  pair_type     VARCHAR( 50 )  NOT NULL,
  [group]       INTEGER        DEFAULT NULL,
  real_load_id  INTEGER        DEFAULT NULL,
  study_type_id INTEGER        DEFAULT NULL
);

CREATE TABLE loads_classes (
  id           INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  class        INTEGER DEFAULT NULL,
  load_id      INTEGER DEFAULT NULL,
  real_load_id INTEGER DEFAULT NULL
);

CREATE TABLE loads_weeks (
  id           INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  week         INTEGER NOT NULL
    DEFAULT '0',
  load_id      INTEGER NOT NULL
    DEFAULT '0',
  real_load_id INTEGER NOT NULL
    DEFAULT '0',
  hours        INTEGER NOT NULL
    DEFAULT '0',
  [group]      INTEGER DEFAULT NULL
);

CREATE TABLE rooms (
  id       INTEGER        NOT NULL,
  name     VARCHAR( 50 )  DEFAULT NULL,
  capacity INTEGER        DEFAULT NULL,
  building INTEGER        DEFAULT NULL,
  chair_id INTEGER        DEFAULT NULL,
  PRIMARY KEY ( id )
);

CREATE TABLE sched (
  id         INTEGER        NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  day        INTEGER        NOT NULL
    DEFAULT '0',
  hour       INTEGER        NOT NULL
    DEFAULT '0',
  [group]    INTEGER        NOT NULL
DEFAULT '0',
  load_id    INTEGER        NOT NULL
    DEFAULT '0',
  room_id    INTEGER        NOT NULL
    DEFAULT '0',
  fixed      INTEGER        NOT NULL
    DEFAULT '0',
  begin_date VARCHAR( 50 )  NOT NULL
    DEFAULT '0',
  end_date   VARCHAR( 50 )  NOT NULL
    DEFAULT '0'
);

CREATE TABLE settings (
  id         INTEGER        NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  begin_date VARCHAR( 50 )  NOT NULL,
  end_date   VARCHAR( 50 )  NOT NULL,
  rev        VARCHAR( 50 )  DEFAULT NULL,
  mtime      VARCHAR( 15 )  DEFAULT NULL
);


CREATE TABLE specialities (
  id         INTEGER         NOT NULL,
  short_name VARCHAR( 50 )   DEFAULT NULL,
  full_name  VARCHAR( 255 )  DEFAULT NULL,
  PRIMARY KEY ( id )
);

CREATE TABLE study_types (
  id        INTEGER        NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  full_name VARCHAR( 60 )  DEFAULT NULL
);

CREATE TABLE subjects (
  id         INTEGER         NOT NULL,
  short_name VARCHAR( 50 )   DEFAULT NULL,
  full_name  VARCHAR( 255 )  DEFAULT NULL,
  PRIMARY KEY ( id )
);

CREATE TABLE teachers (
  id          INTEGER        NOT NULL,
  surname     VARCHAR( 50 )  DEFAULT NULL,
  first_name  VARCHAR( 10 )  DEFAULT NULL,
  second_name VARCHAR( 10 )  DEFAULT NULL,
  class_id    INTEGER        DEFAULT NULL,
  subject_id  INTEGER        DEFAULT NULL,
  room_id     INTEGER        DEFAULT NULL,
  chair_id    INTEGER        DEFAULT NULL,
  status      VARCHAR( 50 )  DEFAULT NULL,
  PRIMARY KEY ( id )
);

CREATE TABLE times (
  id   INTEGER        NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  time VARCHAR( 50 )  DEFAULT NULL
);